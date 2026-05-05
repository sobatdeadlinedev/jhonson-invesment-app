<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserLevelController extends Controller
{
    // ─── Bonus rate per level ───────────────────────────────────────────────
    const BONUS_RATE = [
        1  => 0.005,   // 0.5%
        2  => 0.010,   // 1.0%
        3  => 0.015,   // 1.5%
        4  => 0.020,   // 2.0%
        5  => 0.025,   // 2.5%
        6  => 0.030,   // 3.0%
        7  => 0.035,   // 3.5%
        8  => 0.040,   // 4.0%
        9  => 0.045,   // 4.5%
        10 => 0.050,   // 5.0%
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Hitung periode minggu saat ini.
     * Periode: Rabu 00:00 – Selasa 23:59:59 (gajian setiap Rabu).
     */
    public static function getCurrentWeekPeriod(): array
    {
        $now = Carbon::now();

        $dayOfWeek = $now->dayOfWeek;

        // Hitung berapa hari sejak Rabu terakhir
        $daysFromLastWed = ($dayOfWeek >= 3) ? ($dayOfWeek - 3) : ($dayOfWeek + 4);

        $start = $now->copy()->subDays($daysFromLastWed)->startOfDay();
        $end   = $start->copy()->addDays(6)->endOfDay();

        return [$start, $end];
    }

    /**
     * Tentukan start volume untuk user tertentu.
     *
     * Logika:
     * - Jika level di-set SETELAH weekStart (misal naik level hari Minggu),
     *   maka volume dihitung mulai hari level di-set (bukan dari Rabu).
     * - Jika level di-set SEBELUM atau SAMA dengan weekStart (sudah ada sebelum Rabu),
     *   maka tetap pakai weekStart (Rabu) seperti biasa.
     * - Minggu berikutnya, weekStart sudah baru (Rabu), levelSetAt < weekStart baru,
     *   sehingga otomatis kembali ke periode normal Rabu–Selasa.
     */
    protected function resolveVolumeStart(User $user, Carbon $weekStart): Carbon
    {
        $levelSetAt = $user->userLevel?->updated_at;

        if ($levelSetAt && $levelSetAt->gt($weekStart)) {
            // Level naik di tengah periode → mulai dari hari level di-set
            return $levelSetAt->copy()->startOfDay();
        }

        // Normal → mulai dari Rabu
        return $weekStart->copy();
    }

    /**
     * Ambil semua downline (rekursif) sampai $maxLevel level ke bawah.
     */
    protected function getAllDownlines(User $user, int $maxLevel = 10): \Illuminate\Support\Collection
    {
        $allDownlines = collect();
        $currentLevel = collect([$user]);

        for ($i = 1; $i <= $maxLevel; $i++) {
            $nextLevel = collect();
            foreach ($currentLevel as $u) {
                $children  = $u->referrals->map->referred->filter();
                $nextLevel = $nextLevel->merge($children);
            }

            if ($nextLevel->isEmpty()) break;

            $allDownlines  = $allDownlines->merge($nextLevel);
            $currentLevel  = $nextLevel;
        }

        return $allDownlines;
    }

    /**
     * Ambil downline per level (1–maxLevel).
     */
    protected function getDownlinesByLevel(User $user, int $maxLevel = 10): array
    {
        $levels       = [];
        $currentLevel = collect([$user]);

        for ($i = 1; $i <= $maxLevel; $i++) {
            $nextLevel = collect();
            foreach ($currentLevel as $u) {
                $children  = $u->referrals->map->referred->filter();
                $nextLevel = $nextLevel->merge($children);
            }

            $levels[$i]   = $nextLevel;
            $currentLevel = $nextLevel;

            if ($nextLevel->isEmpty()) {
                for ($j = $i + 1; $j <= $maxLevel; $j++) {
                    $levels[$j] = collect();
                }
                break;
            }
        }

        return $levels;
    }

    /**
     * Hitung total achieved_volume downline dalam rentang waktu tertentu.
     */
    protected function getWeeklyVolume(\Illuminate\Support\Collection $downlines, Carbon $start, Carbon $end): float
    {
        if ($downlines->isEmpty()) return 0;

        $downlineIds = $downlines->pluck('id')->toArray();

        return \App\Models\AchievedVolumeLog::whereIn('user_id', $downlineIds)
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');
    }


    // ─── Controller Methods ───────────────────────────────────────────────────

    /**
     * Tampilkan halaman daftar user beserta level manual, team, dan gaji mingguan.
     */
    public function index()
    {
        [$weekStart, $weekEnd] = self::getCurrentWeekPeriod();

        $users = User::role('member')
            ->with([
                'userLevel.assignedBy',
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred',
            ])
            ->withCount('referrals')
            ->latest()
            ->get();

        $userStats = [];
        foreach ($users as $user) {
            $downlines  = $this->getAllDownlines($user, 10);
            $totalTeam  = $downlines->count();
            $activeTeam = $downlines->where('is_verified', true)->count();

            $level = $user->userLevel?->level;
            $rate  = $level ? (self::BONUS_RATE[$level] ?? 0) : 0;

            // ▼ Gunakan start yang menyesuaikan tanggal level di-set
            $userStart    = $this->resolveVolumeStart($user, $weekStart);
            $weeklyVolume = $this->getWeeklyVolume($downlines, $userStart, $weekEnd);
            $weeklySalary = $weeklyVolume * $rate;

            $userStats[$user->id] = [
                'totalTeam'    => $totalTeam,
                'activeTeam'   => $activeTeam,
                'weeklyVolume' => $weeklyVolume,
                'weeklySalary' => $weeklySalary,
                'rate'         => $rate,
                'volumeStart'  => $userStart,   // untuk ditampilkan di view jika perlu
            ];
        }

        return view('admin.pages.user-level.index', compact(
            'users',
            'userStats',
            'weekStart',
            'weekEnd'
        ));
    }

    /**
     * Tampilkan detail team dan breakdown gaji per level untuk 1 user (AJAX).
     */
    public function show(User $user)
    {
        [$weekStart, $weekEnd] = self::getCurrentWeekPeriod();

        $user->load([
            'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred',
            'userLevel',
        ]);

        $levelData  = $this->getDownlinesByLevel($user, 10);
        $downlines  = $this->getAllDownlines($user, 10);
        $totalTeam  = $downlines->count();
        $activeTeam = $downlines->where('is_verified', true)->count();

        $agentLevel = $user->userLevel?->level;
        $rate       = $agentLevel ? (self::BONUS_RATE[$agentLevel] ?? 0) : 0;

        // ▼ Resolve start yang menyesuaikan tanggal level di-set
        $userStart    = $this->resolveVolumeStart($user, $weekStart);
        $weeklyVolume = $this->getWeeklyVolume($downlines, $userStart, $weekEnd);
        $weeklySalary = $weeklyVolume * $rate;

        // Breakdown per referral level
        $breakdown = [];
        for ($i = 1; $i <= 10; $i++) {
            $members = $levelData[$i] ?? collect();
            if ($members->isEmpty()) continue;

            $vol = $this->getWeeklyVolume($members, $userStart, $weekEnd);

            $breakdown[$i] = [
                'count'        => $members->count(),
                'activeCount'  => $members->where('is_verified', true)->count(),
                'weeklyVolume' => $vol,
            ];
        }

        return response()->json([
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'agentLevel'   => $agentLevel,
            'rate'         => $rate * 100,
            'totalTeam'    => $totalTeam,
            'activeTeam'   => $activeTeam,
            'weeklyVolume' => $weeklyVolume,
            'weeklySalary' => $weeklySalary,
            // ▼ weekStart di-response sesuai userStart (bukan weekStart global)
            'weekStart'    => $userStart->format('d M Y'),
            'weekEnd'      => $weekEnd->format('d M Y'),
            'breakdown'    => $breakdown,
            // ▼ Flag apakah start-nya custom (berguna untuk UI)
            'isCustomStart' => $userStart->ne($weekStart),
        ]);
    }

    /**
     * Simpan atau update level manual user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'level'   => ['required', 'integer', 'min:1', 'max:10'],
            'note'    => ['nullable', 'string', 'max:255'],
        ]);

        UserLevel::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'level'       => $request->level,
                'note'        => $request->note,
                'assigned_by' => auth()->id(),
            ]
        );

        return back()->with('success', 'Level berhasil diatur.');
    }

    /**
     * Hapus level manual user (reset ke tanpa level).
     */
    public function destroy(int $userId)
    {
        UserLevel::where('user_id', $userId)->delete();

        return back()->with('success', 'Level berhasil dihapus.');
    }
}