<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Rekursif: ambil user di level tertentu dari root user.
     * Level 1 = direct referrals, level 2 = referrals of referrals, dst.
     */
    protected function getUsersAtLevel(User $user, int $targetLevel, int $currentLevel = 1): \Illuminate\Support\Collection
    {
        if ($currentLevel === $targetLevel) {
            return $user->referrals->map->referred->filter();
        }

        return $user->referrals->flatMap(function ($ref) use ($targetLevel, $currentLevel) {
            if (!$ref->referred) return collect();
            return $this->getUsersAtLevel($ref->referred, $targetLevel, $currentLevel + 1);
        })->filter();
    }

    /**
     * Ambil semua users di semua level (1–10) sekaligus.
     * Return: array of collections, index 1–10.
     */
    protected function getAllLevels(User $user, int $maxLevel = 10): array
    {
        $levels = [];
        for ($i = 1; $i <= $maxLevel; $i++) {
            $levels[$i] = $this->getUsersAtLevel($user, $i);
        }
        return $levels;
    }


     public function toggleActive(User $user)
{
    $newStatus = !$user->is_active;
    
    $user->update([
        'is_active' => $newStatus,
    ]);

    $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

    return redirect()->back()->with('success', "User {$user->name} berhasil {$status}.");
}

    public function index()
    {
        $users = User::role('member')
            ->with([
                'roles',
                'transactions' => function ($q) {
                    $q->whereIn('type', ['withdrawal', 'deduction', 'deposit'])->latest();
                },
                // Eager-load 10 level dalam
                'referrals.referred.transactions',
                'referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.referrals.referred.transactions',
                'usedReferral.referrer',
            ])
            ->withCount([
                'transactions as withdrawal_count' => fn($q) => $q->whereIn('type', ['withdrawal', 'deduction']),
                'transactions as deposit_count'    => fn($q) => $q->where('type', 'deposit'),
                'referrals as direct_referral_count',
            ])
            ->latest()
            ->get();

        // Pre-compute level data untuk setiap user agar view tidak terlalu berat
        $userLevelData = [];
        foreach ($users as $user) {
            $levels     = $this->getAllLevels($user, 10);
            $totalTeam  = collect($levels)->sum(fn($col) => $col->count());
            $activeTeam = collect($levels)->sum(fn($col) => $col->where('is_verified', true)->count());

            $depositPerLevel = [];
            $totalDeposit    = 0;
            $wdPerLevel      = [];
            $totalWd         = 0;

            for ($i = 1; $i <= 10; $i++) {
                // Deposit per level
                $dep = $levels[$i]->flatMap->transactions
                    ->where('type', 'deposit')
                    ->where('status', 'approved')
                    ->sum('total_amount');
                $depositPerLevel[$i] = $dep;
                $totalDeposit       += $dep;

                // WD per level (withdrawal + deduction)
                $wd = $levels[$i]->flatMap->transactions
                    ->whereIn('type', ['withdrawal', 'deduction'])
                    ->where('status', 'approved')
                    ->sum('total_amount');
                $wdPerLevel[$i] = $wd;
                $totalWd       += $wd;
            }

            $userLevelData[$user->id] = [
                'levels'          => $levels,
                'totalTeam'       => $totalTeam,
                'activeTeam'      => $activeTeam,
                'depositPerLevel' => $depositPerLevel,
                'totalDeposit'    => $totalDeposit,
                'wdPerLevel'      => $wdPerLevel,
                'totalWd'         => $totalWd,
            ];
        }

        return view('admin.pages.user.index', compact('users', 'userLevelData'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'username'        => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'           => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'           => 'required|string|max:20|unique:users,phone,' . $user->id,
            'achieved_volume' => 'nullable|numeric|min:0',
        ]);

        $user->update([
            'name'            => $request->name,
            'username'        => $request->username,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'achieved_volume' => $request->achieved_volume ?? $user->achieved_volume,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully');
    }
}