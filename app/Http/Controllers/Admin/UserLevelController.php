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
     * Contoh: jika hari ini Jumat 9 Mei 2025,
     *   start = Rabu 7 Mei 2025 00:00:00
     *   end   = Selasa 13 Mei 2025 23:59:59
     */
    public static function getCurrentWeekPeriod(): array
    {
        $now = Carbon::now();

        // Carbon: 0=Sun,1=Mon,...,3=Wed,6=Sat
        $dayOfWeek = $now->dayOfWeek;

        // Hitung berapa hari sejak Rabu terakhir
        // Wed = 3; jika hari ini < 3 (Sun/Mon/Tue), mundur ke Rabu minggu lalu
        $daysFromLastWed = ($dayOfWeek >= 3) ? ($dayOfWeek - 3) : ($dayOfWeek + 4);

        $start = $now->copy()->subDays($daysFromLastWed)->startOfDay();
        $end   = $start->copy()->addDays(6)->endOfDay();

        return [$start, $end];
    }

    /**
     * Ambil semua downline (rekursif) sampai $maxLevel level ke bawah.
     * Return: Collection of Users (flat, semua level).
     */
    protected function getAllDownlines(User $user, int $maxLevel = 10): \Illuminate\Support\Collection
    {
        $allDownlines = collect();
        $currentLevel = collect([$user]);

        for ($i = 1; $i <= $maxLevel; $i++) {
            $nextLevel = collect();
            foreach ($currentLevel as $u) {
                // Pastikan relasi referrals & referred sudah di-load atau lazy-load
                $children = $u->referrals->map->referred->filter();
                $nextLevel = $nextLevel->merge($children);
            }

            if ($nextLevel->isEmpty()) break;

            $allDownlines = $allDownlines->merge($nextLevel);
            $currentLevel = $nextLevel;
        }

        return $allDownlines;
    }

    /**
     * Ambil downline per level (1–maxLevel).
     * Return: array [1 => Collection, 2 => Collection, ...]
     */
    protected function getDownlinesByLevel(User $user, int $maxLevel = 10): array
    {
        $levels       = [];
        $currentLevel = collect([$user]);

        for ($i = 1; $i <= $maxLevel; $i++) {
            $nextLevel = collect();
            foreach ($currentLevel as $u) {
                $children = $u->referrals->map->referred->filter();
                $nextLevel = $nextLevel->merge($children);
            }

            $levels[$i]   = $nextLevel;
            $currentLevel = $nextLevel;

            if ($nextLevel->isEmpty()) {
                // Isi level sisanya dengan collection kosong
                for ($j = $i + 1; $j <= $maxLevel; $j++) {
                    $levels[$j] = collect();
                }
                break;
            }
        }

        return $levels;
    }


    /**
     * Hitung total achieved_volume downline yang DICAPAI dalam periode minggu ini.
     * Menggunakan tabel achieved_volume_logs yang mencatat setiap kenaikan achieved_volume.
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
                // Eager load referral tree sampai 10 level
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred',
            ])
            ->withCount('referrals')
            ->latest()
            ->get();

        // Hitung data per user
        $userStats = [];
        foreach ($users as $user) {
            $downlines   = $this->getAllDownlines($user, 10);
            $totalTeam   = $downlines->count();
            $activeTeam  = $downlines->where('is_verified', true)->count();

            // Volume downline minggu ini
            $weeklyVolume = $this->getWeeklyVolume($downlines, $weekStart, $weekEnd);

            // Gaji = volume × rate sesuai level manual
            $level       = $user->userLevel?->level;
            $rate        = $level ? (self::BONUS_RATE[$level] ?? 0) : 0;
            $weeklySalary = $weeklyVolume * $rate;

            $userStats[$user->id] = [
                'totalTeam'    => $totalTeam,
                'activeTeam'   => $activeTeam,
                'weeklyVolume' => $weeklyVolume,
                'weeklySalary' => $weeklySalary,
                'rate'         => $rate,
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
     * Tampilkan detail team dan breakdown gaji per level untuk 1 user.
     * Dipanggil via AJAX / modal.
     */
    public function show(User $user)
    {
        [$weekStart, $weekEnd] = self::getCurrentWeekPeriod();

        // Load referral tree
        $user->load([
            'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred',
            'userLevel',
        ]);

        $levelData   = $this->getDownlinesByLevel($user, 10);
        $downlines   = $this->getAllDownlines($user, 10);
        $totalTeam   = $downlines->count();
        $activeTeam  = $downlines->where('is_verified', true)->count();

        $agentLevel  = $user->userLevel?->level;
        $rate        = $agentLevel ? (self::BONUS_RATE[$agentLevel] ?? 0) : 0;

        // Volume downline minggu ini (total)
        $weeklyVolume = $this->getWeeklyVolume($downlines, $weekStart, $weekEnd);
        $weeklySalary = $weeklyVolume * $rate;

        // Breakdown per referral level (untuk tabel detail)
        $breakdown = [];
        for ($i = 1; $i <= 10; $i++) {
            $members = $levelData[$i] ?? collect();
            if ($members->isEmpty()) continue;

            $vol = $this->getWeeklyVolume($members, $weekStart, $weekEnd);

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
            'rate'         => $rate * 100,   // kirim sebagai persen
            'totalTeam'    => $totalTeam,
            'activeTeam'   => $activeTeam,
            'weeklyVolume' => $weeklyVolume,
            'weeklySalary' => $weeklySalary,
            'weekStart'    => $weekStart->format('d M Y'),
            'weekEnd'      => $weekEnd->format('d M Y'),
            'breakdown'    => $breakdown,
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