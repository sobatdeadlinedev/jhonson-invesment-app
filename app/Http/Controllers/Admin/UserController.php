<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::role('member')
            ->with([
                'roles',
                'transactions' => function ($q) {
                    $q->whereIn('type', ['withdrawal', 'deduction', 'deposit'])->latest();
                },
                'referrals.referred.transactions',
                'referrals.referred.referrals.referred.transactions',
                'referrals.referred.referrals.referred.referrals.referred.transactions',
                'usedReferral.referrer',
            ])
            ->withCount([
                'transactions as withdrawal_count' => fn($q) => $q->whereIn('type', ['withdrawal', 'deduction']),
                'transactions as deposit_count'    => fn($q) => $q->where('type', 'deposit'),
                'referrals as direct_referral_count',
            ])
            ->latest()
            ->get();

        return view('admin.pages.user.index', compact('users'));
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