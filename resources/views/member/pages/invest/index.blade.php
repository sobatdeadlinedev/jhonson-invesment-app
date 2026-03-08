@extends('member.layouts.app')
@section('content')
    <!-- Scrollable Content Area -->
    <div class="scrollable-content">
        <div class="content-section">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Balance Summary Card -->
            <div class="card-dark shadow-sm p-3 mb-3">
                <div class="row g-3">
                    <div class="col-4">
                        <p class="text-muted mb-1 small">Trade Balance</p>
                        <h6 class="text-white mb-0 fw-bold">$ {{ number_format(auth()->user()->trade_balance, 2) }}</h6>
                    </div>
                    <div class="col-4">
                        <p class="text-muted mb-1 small">Available</p>
                        <h6 class="text-success mb-0 fw-bold">$
                            {{ number_format(auth()->user()->getAvailableTradeBalance(), 2) }}</h6>
                    </div>
                    <div class="col-4">
                        <p class="text-muted mb-1 small">Locked</p>
                        <h6 class="text-warning mb-0 fw-bold">$ {{ number_format(auth()->user()->locked_balance, 2) }}</h6>
                    </div>
                </div>
            </div>

            <!-- Daily PnL Card — Exchange Style -->
            <div class="card-dark shadow-sm mb-3" style="overflow:hidden;">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between px-3 pt-3 pb-2"
                    style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <div class="d-flex align-items-center gap-2">
                        <span style="
                            width: 7px; height: 7px; border-radius: 50%; flex-shrink:0;
                            background: {{ $dailyPnl >= 0 ? '#0ecb81' : '#f6465d' }};
                            box-shadow: 0 0 7px {{ $dailyPnl >= 0 ? '#0ecb81' : '#f6465d' }};
                            animation: xi-blink 1.8s infinite;
                        "></span>
                        <span class="text-white fw-semibold" style="font-size:13px; letter-spacing:.3px;">STARS INVESTMENT</span>
                    </div>
                    <small class="text-muted" style="font-size:11px;">{{ now()->format('d M Y') }}</small>
                </div>

                {{-- PnL Utama --}}
                <div class="text-center px-3 pt-3 pb-2">
                    <p class="text-muted mb-1" style="font-size:11px; letter-spacing:.5px; text-transform:uppercase;">Daily P&L</p>
                    <div class="fw-bold {{ $dailyPnl >= 0 ? 'text-success' : 'text-danger' }}"
                        style="font-size: 30px; letter-spacing: -0.5px; font-variant-numeric: tabular-nums; line-height:1.1;">
                        {{ $dailyPnl >= 0 ? '+' : '' }}{{ number_format($dailyPnl, 2) }}
                        <span style="font-size:14px; font-weight:500; color:#707a8a; margin-left:2px;">USDT</span>
                    </div>
                    @if ($dailyFees > 0)
                        <small style="font-size:11px; color:#707a8a;">
                            <i class="bi bi-lightning-charge-fill me-1" style="color:#f6465d;"></i>Fee: -{{ number_format($dailyFees, 2) }} USDT
                        </small>
                    @endif
                </div>

                {{-- Divider --}}
                <div style="height:1px; background:rgba(255,255,255,0.06); margin: 4px 16px 0;"></div>

                {{-- Stats Row --}}
                <div class="d-flex align-items-center px-2 py-2">

                    {{-- Total Trades --}}
                    <div class="flex-fill text-center py-1">
                        <div class="d-flex justify-content-center mb-1">
                            <span style="width:26px;height:26px;border-radius:7px;background:rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;font-size:13px;color:#60a5fa;">
                                <i class="bi bi-activity"></i>
                            </span>
                        </div>
                        <div class="text-white fw-bold" style="font-size:16px; line-height:1; font-variant-numeric:tabular-nums;">{{ $dailyTrades }}</div>
                        <div style="font-size:10px; color:#707a8a; margin-top:2px;">Trades</div>
                    </div>

                    <div style="width:1px; height:36px; background:rgba(255,255,255,0.07);"></div>

                    {{-- Win --}}
                    <div class="flex-fill text-center py-1">
                        <div class="d-flex justify-content-center mb-1">
                            <span style="width:26px;height:26px;border-radius:7px;background:rgba(14,203,129,0.12);display:flex;align-items:center;justify-content:center;font-size:15px;color:#0ecb81;">
                                <i class="bi bi-arrow-up-short"></i>
                            </span>
                        </div>
                        <div class="text-success fw-bold" style="font-size:16px; line-height:1; font-variant-numeric:tabular-nums;">{{ $dailyWins }}</div>
                        <div style="font-size:10px; color:#707a8a; margin-top:2px;">Win</div>
                    </div>

                    <div style="width:1px; height:36px; background:rgba(255,255,255,0.07);"></div>

                    {{-- Loss --}}
                    <div class="flex-fill text-center py-1">
                        <div class="d-flex justify-content-center mb-1">
                            <span style="width:26px;height:26px;border-radius:7px;background:rgba(246,70,93,0.12);display:flex;align-items:center;justify-content:center;font-size:15px;color:#f6465d;">
                                <i class="bi bi-arrow-down-short"></i>
                            </span>
                        </div>
                        <div class="text-danger fw-bold" style="font-size:16px; line-height:1; font-variant-numeric:tabular-nums;">{{ $dailyLosses }}</div>
                        <div style="font-size:10px; color:#707a8a; margin-top:2px;">Loss</div>
                    </div>

                    <div style="width:1px; height:36px; background:rgba(255,255,255,0.07);"></div>

                    {{-- Win Rate --}}
                    @php $winRate = $dailyTrades > 0 ? round(($dailyWins / $dailyTrades) * 100) : 0; @endphp
                    <div class="flex-fill text-center py-1">
                        <div class="d-flex justify-content-center mb-1">
                            <span style="width:26px;height:26px;border-radius:7px;background:rgba(240,185,11,0.12);display:flex;align-items:center;justify-content:center;font-size:12px;color:#f0b90b;">
                                <i class="bi bi-percent"></i>
                            </span>
                        </div>
                        <div style="font-size:16px; line-height:1; font-variant-numeric:tabular-nums; font-weight:700; color:#f0b90b;">{{ $winRate }}%</div>
                        <div style="font-size:10px; color:#707a8a; margin-top:2px;">Win Rate</div>
                    </div>
                </div>

                {{-- Active Signals Banner --}}
                @if ($activeSignals > 0)
                    <div class="d-flex align-items-center gap-2 px-3 py-2"
                        style="background:rgba(240,185,11,0.07); border-top:1px solid rgba(240,185,11,0.15);">
                        <span style="
                            width:8px; height:8px; border-radius:50%; background:#f0b90b; flex-shrink:0;
                            animation: xi-pulse 1.5s infinite;
                            box-shadow: 0 0 0 0 rgba(240,185,11,0.5);
                        "></span>
                        <small class="text-warning fw-semibold" style="font-size:12px;">
                            {{ $activeSignals }} Active Signal{{ $activeSignals > 1 ? 's' : '' }} Running
                        </small>
                        <span class="ms-auto text-muted" style="font-size:10px; letter-spacing:.5px;">LIVE</span>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <a href="{{ route('member.invest.history') }}" class="btn btn-outline-light w-100 btn-sm">
                        <i class="bi bi-clock-history me-1"></i>Historical Orders
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('member.balance.transfer') }}" class="btn btn-outline-light w-100 btn-sm">
                        <i class="bi bi-arrow-left-right me-1"></i>Transfer Balance
                    </a>
                </div>
            </div>

            <!-- Coins List Card -->
            <div class="card-dark shadow-sm p-0 mb-3">
                <div class="p-3" style="border-bottom: 1px solid var(--border-color);">
                    <h6 class="text-white mb-0">Select Coin for Trading Signals</h6>
                </div>

                @foreach ($coins as $symbol => $info)
                    <!-- Coin Item -->
                    <a href="{{ route('member.invest.detail', ['coin' => strtolower($symbol)]) }}" class="coin-list-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <div class="coin-icon"
                                    style="background: linear-gradient(135deg, {{ $info['color'] }} 0%, {{ $info['color'] }}dd 100%);">
                                    <i class="{{ $info['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-white fw-bold mb-1" style="font-size: 14px;">{{ $info['symbol'] }}
                                    </div>
                                    <small class="text-muted">{{ $info['name'] }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                @if ($signalCounts[$symbol] > 0)
                                    <div class="mb-1">
                                        <span class="badge badge-success" style="font-size: 11px;">
                                            <i class="bi bi-broadcast me-1"></i>{{ $signalCounts[$symbol] }}
                                            Signal{{ $signalCounts[$symbol] > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                @else
                                    <small class="text-muted">No signals</small>
                                @endif
                                <i class="bi bi-chevron-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Info Card -->
            <div class="card-dark shadow-sm p-3 mb-3">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle-fill text-gold" style="font-size: 18px; margin-top: 2px;"></i>
                    <div>
                        <h6 class="text-white mb-1" style="font-size: 13px;">How to Trade</h6>
                        <p class="small text-muted mb-0" style="font-size: 12px;">
                            Select a coin to view available trading signals. Minimum $100.00 available Trade Balance
                            required to join signals.
                            Your bet is calculated as 1% of your Trade Balance.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        </script>
        <style>
            @keyframes xi-blink {
                0%, 100% { opacity: 1; }
                50%       { opacity: 0.25; }
            }
            @keyframes xi-pulse {
                0%   { box-shadow: 0 0 0 0 rgba(240,185,11,0.55); }
                70%  { box-shadow: 0 0 0 7px rgba(240,185,11,0); }
                100% { box-shadow: 0 0 0 0 rgba(240,185,11,0); }
            }
        </style>
    @endpush
@endsection