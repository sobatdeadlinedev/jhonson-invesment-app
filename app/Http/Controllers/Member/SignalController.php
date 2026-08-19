<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\TradingSignal;
use App\Models\SignalParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SignalController extends Controller
{
    /**
     * Join a trading signal
     */
    public function join($id)
    {
        $signal = TradingSignal::findOrFail($id);
        $user = auth()->user();

        // UPDATED: Check if user has access to this signal
        if (!$signal->isUserAllowed($user->id)) {
            return redirect()
                ->back()
                ->with('error', 'You do not have access to this signal.');
        }

        // Validasi signal status
        if ($signal->status !== 'open') {
            return redirect()
                ->back()
                ->with('error', 'This signal is no longer available for joining.');
        }

        // ── DITAMBAH: cegah user join signal yang belum waktunya tayang
        //    (misal join langsung lewat request manual ke route join/{id}
        //    sebelum scheduled_at tiba)
        if (!$signal->isLive()) {
            return redirect()
                ->back()
                ->with('error', 'This signal is not available yet.');
        }

        // Check if user already joined
        $alreadyJoined = SignalParticipant::where('signal_id', $signal->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyJoined) {
            return redirect()
                ->back()
                ->with('error', 'You have already joined this signal.');
        }

        // UPDATED: Calculate bet amount based on signal configuration
        $betAmount = $signal->calculateUserBetAmount($user);

        // UPDATED: Validate minimum balance
        if ($signal->bet_type === 'percentage') {
            // For percentage, check minimum 100 USDT available trade balance
            if (!$user->canJoinSignal()) {
                return redirect()
                    ->back()
                    ->with('error', 'Minimum available Trade Balance to join signal is 100 USDT. Your available balance: ' . number_format($user->getAvailableTradeBalance(), 2) . ' USDT.');
            }
        } else {
            // For fixed amount, check if user has sufficient balance
            if ($user->getAvailableTradeBalance() < $betAmount) {
                return redirect()
                    ->back()
                    ->with('error', 'Insufficient available Trade Balance. Required: ' . number_format($betAmount, 2) . ' USDT. Your available balance: ' . number_format($user->getAvailableTradeBalance(), 2) . ' USDT.');
            }
        }

        try {
            DB::beginTransaction();

            // Lock the bet amount
            $user->lockBalance($betAmount);

            // Create participant record
            $participant = SignalParticipant::create([
                'signal_id' => $signal->id,
                'user_id' => $user->id,
                'bet_amount' => $betAmount,
                'status' => 'joined',
                'joined_at' => now(),
            ]);

            DB::commit();

            Log::info('User joined signal', [
                'user_id' => $user->id,
                'signal_id' => $signal->id,
                'bet_type' => $signal->bet_type,
                'bet_value' => $signal->bet_value,
                'calculated_bet_amount' => $betAmount,
                'locked_balance' => $user->locked_balance,
            ]);

            return redirect()
                ->route('member.invest.detail', ['signal_id' => $signal->id])
                ->with('success', 'Successfully joined signal: ' . $signal->title . '. Bet amount: ' . number_format($betAmount, 2) . ' USDT has been locked.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Join signal failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'signal_id' => $signal->id,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to join signal: ' . $e->getMessage());
        }
    }

    /**
     * Show user's signal history
     */
    public function history()
    {
        $user = auth()->user();

        $participants = SignalParticipant::with('signal')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        // Statistics
        $totalJoined = SignalParticipant::where('user_id', $user->id)->count();
        $totalSettled = SignalParticipant::where('user_id', $user->id)->settled()->count();
        $totalProfitLoss = SignalParticipant::where('user_id', $user->id)->settled()->sum('profit_loss');
        $totalFees = SignalParticipant::where('user_id', $user->id)->settled()->sum('fee_amount');
        $totalWins = SignalParticipant::where('user_id', $user->id)
            ->settled()
            ->where('profit_loss', '>', 0)
            ->count();

        return view('member.pages.invest.history', [
            'participants' => $participants,
            'totalJoined' => $totalJoined,
            'totalSettled' => $totalSettled,
            'totalProfitLoss' => $totalProfitLoss,
            'totalFees' => $totalFees,
            'totalWins' => $totalWins,
            'winRate' => $totalSettled > 0 ? ($totalWins / $totalSettled) * 100 : 0,
        ]);
    }
}
