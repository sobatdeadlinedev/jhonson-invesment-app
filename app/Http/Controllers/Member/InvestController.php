<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\TradingSignal;
use App\Models\SignalParticipant;
use Illuminate\Http\Request;

class InvestController extends Controller
{
    /**
     * Show coins list with signal count
     */
    public function index()
    {
        $user = auth()->user();
        $coins = TradingSignal::getAvailableCoins();

        // Count open signals per coin yang user bisa akses
        $signalCounts = [];
        foreach (array_keys($coins) as $coinSymbol) {
            $signalCounts[$coinSymbol] = TradingSignal::forCoin($coinSymbol)
                ->open()
                ->accessibleBy($user->id) // UPDATED: Filter by access
                ->count();
        }

        return view('member.pages.invest.index', compact('coins', 'signalCounts'));
    }

    /**
     * Show signals for specific coin
     */
    public function detail(Request $request)
    {
        $user = auth()->user();

        // Get coin from query parameter OR signal_id
        if ($request->has('signal_id')) {
            // Direct signal access
            $signalId = $request->query('signal_id');
            $signal = TradingSignal::with('creator')
                ->withCount('participants')
                ->findOrFail($signalId);

            // UPDATED: Check if user has access to this signal
            if (!$signal->isUserAllowed($user->id)) {
                abort(403, 'You do not have access to this signal.');
            }

            $participant = SignalParticipant::where('signal_id', $signal->id)
                ->where('user_id', $user->id)
                ->first();

            $hasJoined = !is_null($participant);

            // UPDATED: Calculate bet amount preview based on signal config
            $betAmountPreview = $signal->calculateUserBetAmount($user);

            return view('member.pages.invest.detail', compact('signal', 'participant', 'hasJoined', 'betAmountPreview'));
        }

        // Coin signals list
        $coin = strtoupper($request->query('coin', 'BTCUSDT'));
        $coinInfo = TradingSignal::getAvailableCoins()[$coin] ?? TradingSignal::getAvailableCoins()['BTCUSDT'];

        // UPDATED: Filter only accessible signals
        $openSignals = TradingSignal::forCoin($coin)
            ->open()
            ->accessibleBy($user->id)
            ->with('creator')
            ->withCount('participants')
            ->latest()
            ->get();

        $joinedSignalIds = SignalParticipant::where('user_id', $user->id)
            ->pluck('signal_id')
            ->toArray();

        // UPDATED: Add bet amount preview for each signal
        $openSignals->each(function ($signal) use ($user) {
            $signal->betAmountPreview = $signal->calculateUserBetAmount($user);
        });

        return view('member.pages.invest.coin-signals', compact('coin', 'coinInfo', 'openSignals', 'joinedSignalIds'));
    }
}
