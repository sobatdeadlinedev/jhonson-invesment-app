<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WithdrawalController extends Controller
{
    public function index()
    {
        // Gabungkan withdrawal dan deduction
        $withdrawals = Transaction::with(['user', 'wallet'])
            ->whereIn('type', ['withdrawal', 'deduction'])
            ->latest()
            ->get();

        $members = User::role('member')
            ->orderBy('name')
            ->get();

        return view('admin.pages.withdrawal.index', compact('withdrawals', 'members'));
    }

    public function show($id)
    {
        $withdrawal = Transaction::with(['user', 'wallet', 'approver'])
            ->withdrawal()
            ->findOrFail($id);

        return view('admin.pages.withdrawal.detail', compact('withdrawal'));
    }

    public function approve(Request $request, $id)
    {
        $withdrawal = Transaction::withdrawal()->findOrFail($id);

        // Check if already processed
        if ($withdrawal->status !== 'pending') {
            return redirect()->route('admin.withdrawal.index')
                ->with('error', 'This withdrawal has already been processed.');
        }

        // Validate payment proof upload - OPTIONAL
        $request->validate([
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB
        ], [
            'payment_proof.image' => 'Payment proof must be an image.',
            'payment_proof.mimes' => 'Payment proof must be a file of type: jpeg, png, jpg.',
            'payment_proof.max' => 'Payment proof must not be greater than 5MB.',
        ]);

        try {
            DB::beginTransaction();

            $paymentProofPath = null;

            // Store payment proof if uploaded
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'approved',
                'payment_proof' => $paymentProofPath,
                'approved_by' => auth()->id(),
            ]);

            // Balance sudah dikurangi saat pending, tidak perlu update lagi

            DB::commit();

            return redirect()->route('admin.withdrawal.index')
                ->with('success', 'Withdrawal has been approved and completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.withdrawal.index')
                ->with('error', 'Failed to approve withdrawal: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $withdrawal = Transaction::withdrawal()->findOrFail($id);

        // Check if already processed
        if ($withdrawal->status !== 'pending') {
            return redirect()->route('admin.withdrawal.index')
                ->with('error', 'This withdrawal has already been processed.');
        }

        try {
            DB::beginTransaction();

            $user = $withdrawal->user;

            // Return balance to exchange balance
            $user->addExchangeBalance($withdrawal->total_amount);

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.withdrawal.index')
                ->with('success', 'Withdrawal has been rejected and balance has been returned to user.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.withdrawal.index')
                ->with('error', 'Failed to reject withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Manual deduction (reduce balance)
     */
    public function deduction(Request $request)
    {
        // Validate input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'balance_type' => 'required|in:exchange,trade',
        ], [
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'Selected user not found.',
            'amount.required' => 'Please enter the amount.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be at least 0.01 USDT.',
            'balance_type.required' => 'Please select balance type.',
            'balance_type.in' => 'Invalid balance type selected.',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $amount = $request->amount;
            $balanceType = $request->balance_type;

            // Get current balance
            $currentBalance = $balanceType === 'trade' ? $user->trade_balance : $user->exchange_balance;

            // Check if user has sufficient balance
            if (!Transaction::hasSufficientBalance($user->id, $amount, $balanceType)) {
                DB::rollBack();

                return back()->with('error', "Insufficient balance! User only has " . number_format($currentBalance, 2) . " USDT in {$balanceType} balance. Cannot deduct " . number_format($amount, 2) . " USDT.");
            }

            DB::beginTransaction();

            // Create deduction transaction
            Transaction::create([
                'user_id' => $user->id,
                'reference' => Transaction::generateReference('DED'),
                'amount' => $amount,
                'total_amount' => $amount,
                'type' => 'deduction',
                'balance_type' => $balanceType,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'wallet_id' => null,
                'withdrawal_fee' => null,
                'source_user_id' => null,
                'payment_method' => null,
                'payment_proof' => null,
            ]);

            // Deduct balance based on type
            if ($balanceType === 'trade') {
                $user->deductTradeBalance($amount);
            } else {
                $user->deductExchangeBalance($amount);
            }

            DB::commit();

            return redirect()->route('admin.withdrawal.index')
                ->with('success', "Successfully deducted " . number_format($amount, 2) . " USDT from {$user->name}'s {$balanceType} balance.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.withdrawal.index')
                ->with('error', 'Failed to deduct balance: ' . $e->getMessage());
        }
    }
}
