<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserLevelController extends Controller
{
    /**
     * Tampilkan halaman daftar user beserta level manual mereka.
     */
    public function index()
    {
        $users = User::role('member')
            ->with('userLevel.assignedBy')
            ->withCount('referrals')
            ->latest()
            ->get();

        return view('admin.pages.user-level.index', compact('users'));
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