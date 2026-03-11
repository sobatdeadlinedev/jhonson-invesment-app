@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── SESSION ALERTS ───────────────────────────────── --}}
            @if (session('success'))
                <div style="background:rgba(0,212,138,.08);border:1px solid rgba(0,212,138,.25);border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <i class="bi bi-check-circle-fill" style="color:#00d48a;font-size:15px;flex-shrink:0;"></i>
                    <span style="font-size:13px;color:#00d48a;">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div style="background:rgba(240,79,90,.08);border:1px solid rgba(240,79,90,.25);border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <i class="bi bi-exclamation-circle-fill" style="color:#f04f5a;font-size:15px;flex-shrink:0;"></i>
                    <span style="font-size:13px;color:#f04f5a;">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ── BALANCE SUMMARY ──────────────────────────────── --}}
            <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:16px;position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(0,212,138,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(0,212,138,.025) 1px,transparent 1px);background-size:28px 28px;"></div>
                <div style="position:absolute;top:-25px;right:-25px;width:120px;height:120px;border-radius:50%;background:radial-gradient(circle,rgba(0,212,138,.10) 0%,transparent 65%);pointer-events:none;"></div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;position:relative;">
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Trade</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#e2eaf8;">$&nbsp;{{ number_format(auth()->user()->trade_balance, 2) }}</div>
                    </div>
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Available</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#00d48a;">$&nbsp;{{ number_format(auth()->user()->getAvailableTradeBalance(), 2) }}</div>
                    </div>
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Locked</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#f5a623;">$&nbsp;{{ number_format(auth()->user()->locked_balance, 2) }}</div>
                    </div>
                </div>
            </div>

            {{-- ── DAILY PNL CARD ───────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">

                {{-- Header --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:7px;height:7px;border-radius:50%;flex-shrink:0;background:{{ $dailyPnl >= 0 ? '#00d48a' : '#f04f5a' }};box-shadow:0 0 7px {{ $dailyPnl >= 0 ? '#00d48a' : '#f04f5a' }};animation:xi-blink 1.8s infinite;"></span>
                        <span style="font-size:13px;font-weight:700;color:#e2eaf8;letter-spacing:.3px;">STARS INVESTMENT</span>
                    </div>
                    <span style="font-size:11px;color:#3a4d66;">{{ now()->format('d M Y') }}</span>
                </div>

                {{-- PnL Utama --}}
                <div style="text-align:center;padding:20px 16px 16px;">
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Daily P&amp;L</div>
                    <div style="font-family:monospace;font-size:32px;font-weight:700;letter-spacing:-1px;line-height:1;color:{{ $dailyPnl >= 0 ? '#00d48a' : '#f04f5a' }};">
                        {{ $dailyPnl >= 0 ? '+' : '' }}{{ number_format($dailyPnl, 2) }}
                        <span style="font-size:14px;font-weight:500;color:#3a4d66;margin-left:3px;">USDT</span>
                    </div>
                    @if ($dailyFees > 0)
                        <div style="margin-top:6px;font-size:11px;color:#3a4d66;">
                            <i class="bi bi-lightning-charge-fill" style="color:#f04f5a;margin-right:3px;"></i>Fee: -{{ number_format($dailyFees, 2) }} USDT
                        </div>
                    @endif
                </div>

                {{-- Divider --}}
                <div style="height:1px;background:#1a2235;margin:0 16px;"></div>

                {{-- Stats Row --}}
                @php $winRate = $dailyTrades > 0 ? round(($dailyWins / $dailyTrades) * 100) : 0; @endphp
                <div style="display:grid;grid-template-columns:1fr 1px 1fr 1px 1fr 1px 1fr;padding:4px 0;">

                    <div style="text-align:center;padding:10px 4px;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(100,160,255,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 6px;">
                            <i class="bi bi-activity" style="font-size:12px;color:#64a0ff;"></i>
                        </div>
                        <div style="font-family:monospace;font-size:16px;font-weight:700;color:#e2eaf8;line-height:1;">{{ $dailyTrades }}</div>
                        <div style="font-size:10px;color:#3a4d66;margin-top:2px;">Trades</div>
                    </div>

                    <div style="background:#1a2235;"></div>

                    <div style="text-align:center;padding:10px 4px;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(0,212,138,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 6px;">
                            <i class="bi bi-arrow-up-short" style="font-size:15px;color:#00d48a;"></i>
                        </div>
                        <div style="font-family:monospace;font-size:16px;font-weight:700;color:#00d48a;line-height:1;">{{ $dailyWins }}</div>
                        <div style="font-size:10px;color:#3a4d66;margin-top:2px;">Win</div>
                    </div>

                    <div style="background:#1a2235;"></div>

                    <div style="text-align:center;padding:10px 4px;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(240,79,90,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 6px;">
                            <i class="bi bi-arrow-down-short" style="font-size:15px;color:#f04f5a;"></i>
                        </div>
                        <div style="font-family:monospace;font-size:16px;font-weight:700;color:#f04f5a;line-height:1;">{{ $dailyLosses }}</div>
                        <div style="font-size:10px;color:#3a4d66;margin-top:2px;">Loss</div>
                    </div>

                    <div style="background:#1a2235;"></div>

                    <div style="text-align:center;padding:10px 4px;">
                        <div style="width:26px;height:26px;border-radius:7px;background:rgba(245,166,35,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 6px;">
                            <i class="bi bi-percent" style="font-size:11px;color:#f5a623;"></i>
                        </div>
                        <div style="font-family:monospace;font-size:16px;font-weight:700;color:#f5a623;line-height:1;">{{ $winRate }}%</div>
                        <div style="font-size:10px;color:#3a4d66;margin-top:2px;">Win Rate</div>
                    </div>

                </div>

                {{-- Active Signals Banner --}}
                @if ($activeSignals > 0)
                    <div style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:rgba(245,166,35,.06);border-top:1px solid rgba(245,166,35,.14);">
                        <span style="width:8px;height:8px;border-radius:50%;background:#f5a623;flex-shrink:0;animation:xi-pulse 1.5s infinite;"></span>
                        <span style="font-size:12px;font-weight:700;color:#f5a623;">{{ $activeSignals }} Active Signal{{ $activeSignals > 1 ? 's' : '' }} Running</span>
                        <span style="display:inline-flex;align-items:center;gap:4px;margin-left:auto;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);border-radius:99px;padding:2px 8px;font-size:10px;font-weight:700;color:#f5a623;letter-spacing:.5px;">
                            <span style="width:5px;height:5px;border-radius:50%;background:#f5a623;animation:xi-blink 1.6s infinite;"></span>
                            LIVE
                        </span>
                    </div>
                @endif
            </div>

            {{-- ── QUICK ACTIONS ────────────────────────────────── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                <a href="{{ route('member.invest.history') }}" style="background:#0d1120;border:1px solid #1a2235;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:9px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,.03)'" onmouseout="this.style.background='#0d1120'">
                    <div style="width:32px;height:32px;border-radius:9px;background:rgba(100,160,255,.10);border:1px solid rgba(100,160,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-clock-history" style="font-size:14px;color:#64a0ff;"></i>
                    </div>
                    <span style="font-size:12px;font-weight:600;color:#e2eaf8;">Historical Orders</span>
                </a>
                <a href="{{ route('member.balance.transfer') }}" style="background:#0d1120;border:1px solid #1a2235;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:9px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,.03)'" onmouseout="this.style.background='#0d1120'">
                    <div style="width:32px;height:32px;border-radius:9px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-arrow-left-right" style="font-size:14px;color:#00d48a;"></i>
                    </div>
                    <span style="font-size:12px;font-weight:600;color:#e2eaf8;">Transfer Balance</span>
                </a>
            </div>

            {{-- ── COINS LIST ───────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">

                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Select Coin for Trading Signals</span>
                </div>

                @foreach ($coins as $symbol => $info)
                    <a href="{{ route('member.invest.detail', ['coin' => strtolower($symbol)]) }}" class="xi-coin-row">
                        <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                            <div style="width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,{{ $info['color'] }} 0%,{{ $info['color'] }}aa 100%);display:flex;align-items:center;justify-content:center;font-size:17px;color:#fff;flex-shrink:0;">
                                <i class="{{ $info['icon'] }}"></i>
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:2px;">{{ $info['symbol'] }}</div>
                                <div style="font-size:10px;color:#3a4d66;">{{ $info['name'] }}</div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                            @if ($signalCounts[$symbol] > 0)
                                <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.20);border-radius:99px;padding:3px 9px;font-size:11px;font-weight:700;color:#00d48a;">
                                    <i class="bi bi-broadcast" style="font-size:10px;"></i>
                                    {{ $signalCounts[$symbol] }} Signal{{ $signalCounts[$symbol] > 1 ? 's' : '' }}
                                </span>
                            @else
                                <span style="font-size:11px;color:#3a4d66;">No signals</span>
                            @endif
                            <i class="bi bi-chevron-right" style="font-size:12px;color:#3a4d66;"></i>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- ── INFO CARD ────────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-left:3px solid #f5a623;border-radius:14px;padding:14px 16px;display:flex;align-items:flex-start;gap:10px;">
                <i class="bi bi-info-circle-fill" style="font-size:16px;color:#f5a623;flex-shrink:0;margin-top:1px;"></i>
                <div>
                    <div style="font-size:12px;font-weight:700;color:#e2eaf8;margin-bottom:4px;">How to Trade</div>
                    <div style="font-size:12px;color:#7a8fad;line-height:1.55;">
                        Select a coin to view available trading signals. Minimum $100.00 available Trade Balance
                        required to join signals. Your bet is calculated as 1% of your Trade Balance.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .xi-coin-row {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 13px 16px !important;
            border-bottom: 1px solid rgba(26,34,53,.9) !important;
            text-decoration: none !important;
            transition: background .15s;
        }
        .xi-coin-row:last-child { border-bottom: none !important; }
        .xi-coin-row:hover { background: rgba(255,255,255,.02) !important; }
    </style>

    @push('scripts')
        <script>
            setTimeout(function() { $('.alert').fadeOut('slow'); }, 5000);
        </script>
        <style>
            @keyframes xi-blink {
                0%, 100% { opacity: 1; }
                50%       { opacity: 0.2; }
            }
            @keyframes xi-pulse {
                0%   { box-shadow: 0 0 0 0 rgba(245,166,35,.55); }
                70%  { box-shadow: 0 0 0 7px rgba(245,166,35,0); }
                100% { box-shadow: 0 0 0 0 rgba(245,166,35,0); }
            }
        </style>
    @endpush
@endsection