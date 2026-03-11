<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    public function index()
    {
        $verifications = UserVerification::with('user')
            ->submitted()
            ->latest('submitted_at')
            ->paginate(50);

        return view('admin.pages.verification.index', compact('verifications'));
    }

    public function show($id)
    {
        $verification = UserVerification::with('user')->findOrFail($id);

        return view('admin.pages.verification.detail', compact('verification'));
    }

    public function verify($id)
    {
        try {
            DB::beginTransaction();

            $verification = UserVerification::findOrFail($id);

            // Cek apakah sudah diverifikasi sebelumnya
            if ($verification->isVerified()) {
                return redirect()->back()->with('error', 'This account has already been verified.');
            }

            // Update verified_at di user_verifications
            $verification->markAsVerified();

            // Update is_verified di users
            $verification->user->verify();

            DB::commit();

            return redirect()->route('admin.verification.show', $id)
                ->with('success', 'Account has been successfully verified!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to verify account. Please try again.');
        }
    }

    public function reject($id)
    {
        try {
            DB::beginTransaction();

            $verification = UserVerification::findOrFail($id);

            // Cek apakah sudah diverifikasi
            if ($verification->isVerified()) {
                return redirect()->back()->with('error', 'Cannot reject a verified account.');
            }

            // Reset submitted_at untuk allow user submit ulang
            $verification->update([
                'submitted_at' => null,
                'verified_at' => null
            ]);

            // Pastikan user tetap unverified
            $verification->user->unverify();

            DB::commit();

            return redirect()->route('admin.verification.index')
                ->with('success', 'Verification has been rejected. User can resubmit their documents.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to reject verification. Please try again.');
        }
    }
}
