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
    // ── DITAMBAH: ->live() supaya signal yang scheduled_at-nya masih di masa
    //    depan tidak ikut dihitung/ditampilkan sebelum waktunya tayang
    $signalCounts = [];
    foreach (array_keys($coins) as $coinSymbol) {
        $signalCounts[$coinSymbol] = TradingSignal::forCoin($coinSymbol)
            ->open()
            ->live()
            ->accessibleBy($user->id)
            ->count();
    }

    // === TAMBAHAN: Daily PnL ===
    $today = now()->toDateString();

    $dailyStats = SignalParticipant::where('user_id', $user->id)
        ->whereDate('updated_at', $today) // atau 'settled_at' jika ada kolom itu
        ->whereIn('status', ['win', 'loss', 'settled'])
        ->selectRaw('
            COUNT(*) as total_trades,
            SUM(CASE WHEN profit_loss > 0 THEN 1 ELSE 0 END) as wins,
            SUM(CASE WHEN profit_loss < 0 THEN 1 ELSE 0 END) as losses,
            SUM(profit_loss) as total_pnl,
            SUM(fee_amount) as total_fees
        ')
        ->first();

    $dailyPnl = $dailyStats->total_pnl ?? 0;
    $dailyTrades = $dailyStats->total_trades ?? 0;
    $dailyWins = $dailyStats->wins ?? 0;
    $dailyLosses = $dailyStats->losses ?? 0;
    $dailyFees = $dailyStats->total_fees ?? 0;
    $dailyWinRate = $dailyTrades > 0 ? ($dailyWins / $dailyTrades) * 100 : 0;

    // Active/joined signals hari ini (belum settled)
    $activeSignals = SignalParticipant::where('user_id', $user->id)
        ->where('status', 'joined')
        ->count();

    return view('member.pages.invest.index', compact(
        'coins',
        'signalCounts',
        'dailyPnl',
        'dailyTrades',
        'dailyWins',
        'dailyLosses',
        'dailyFees',
        'dailyWinRate',
        'activeSignals'
    ));
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

            // ── DITAMBAH: cegah user akses langsung via URL (signal_id manual)
            //    sebelum jadwal tayangnya tiba. Admin/creator tetap bisa lihat
            //    kalau nanti perlu di-exclude, tapi untuk member biasa ini di-block.
            if (!$signal->isLive()) {
                abort(404, 'Signal belum tersedia.');
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
        // ── DITAMBAH: ->live() supaya signal yang scheduled_at-nya belum
        //    tiba tidak muncul di daftar signal per-coin
        $openSignals = TradingSignal::forCoin($coin)
            ->open()
            ->live()
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
