@extends('member.layouts.app')
@section('content')
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

            <div class="mb-3">
                <a href="{{ route('member.invest.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Back to Coins
                </a>
            </div>

            <div class="card-dark shadow-sm p-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="coin-icon-large"
                        style="background: linear-gradient(135deg, {{ $coinInfo['color'] }} 0%, {{ $coinInfo['color'] }}dd 100%);">
                        <i class="{{ $coinInfo['icon'] }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="text-white mb-1 fw-bold">{{ $coinInfo['symbol'] }}</h5>
                        <small class="text-muted">{{ $coinInfo['name'] }}</small>
                    </div>
                    <div class="text-end">
                        <h6 class="text-gold mb-0 fw-bold">{{ count($openSignals) }}</h6>
                        <small class="text-muted">Open Signals</small>
                    </div>
                </div>
            </div>

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

            @if (!auth()->user()->canJoinSignal())
                <div class="alert alert-danger" style="font-size: 12px;">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Notice:</strong> Minimum $ 100.00 available Trade Balance required to join signals.
                    Please <a href="{{ route('member.balance.transfer') }}" class="text-white"><u>transfer funds</u></a>
                    first.
                </div>
            @endif

            {{-- CHART SECTION --}}
            <div class="card-dark shadow-sm p-3 mb-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-white mb-0">{{ $coinInfo['name'] }} Chart</h6>
                    <div class="chart-timeframe-pills">
                        <button class="timeframe-pill active" data-interval="60">1H</button>
                        <button class="timeframe-pill" data-interval="D">1D</button>
                        <button class="timeframe-pill" data-interval="W">1W</button>
                    </div>
                </div>

                <div class="chart-container" style="height: 400px;">
                    <iframe id="tradingViewChart"
                        src="https://www.tradingview.com/widgetembed/?symbol={{ $coinInfo['tradingview_symbol'] }}&interval=60&theme=dark&style=1&locale=en&toolbar_bg=1d2058&hidesidetoolbar=1&hidetoptoolbar=1&symboledit=0&saveimage=0&withdateranges=0&allow_symbol_change=0&show_popup_button=0&details=0&calendar=0&studies=%5B%5D"
                        style="width: 100%; height: 100%; border: none; border-radius: 8px;" frameborder="0"
                        allowtransparency="true" scrolling="no">
                    </iframe>
                </div>
            </div>

            <div class="card-dark shadow-sm p-0 mb-3">
                <div class="p-3" style="border-bottom: 1px solid var(--border-color);">
                    <h6 class="text-white mb-0">Available Trading Signals</h6>
                </div>

                @forelse($openSignals as $signal)
                    @php
                        // Check if signal is pending
                        $isPending = $signal->result === 'pending' || $signal->result === null;
                    @endphp

                    <div class="coin-list-item">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="text-white fw-bold mb-1" style="font-size: 14px;">
                                        <i class="bi bi-broadcast text-gold me-2"></i>{{ $signal->title }}
                                    </div>
                                    @if ($signal->description)
                                        <small
                                            class="text-muted d-block">{{ Str::limit($signal->description, 60) }}</small>
                                    @endif
                                </div>
                                <span class="badge badge-success" style="font-size: 10px;">
                                    <i class="bi bi-circle-fill" style="font-size: 6px;"></i> OPEN
                                </span>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 10px;">Opening Price</small>
                                    <small class="text-white fw-bold">
                                        @if ($isPending || !$signal->entry_price)
                                            ~
                                        @else
                                            $ {{ number_format($signal->entry_price, 2) }}
                                        @endif
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 10px;">Settlement Price</small>
                                    <small class="text-gold fw-bold">
                                        @if ($isPending || !$signal->target_price)
                                            ~
                                        @else
                                            $ {{ number_format($signal->target_price, 2) }}
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2"
                                style="border-top: 1px solid var(--border-color);">
                                @if (in_array($signal->id, $joinedSignalIds))
                                    <span class="badge bg-primary" style="font-size: 11px;">
                                        <i class="bi bi-check-circle me-1"></i>Joined
                                    </span>
                                @else
                                    <a href="{{ route('member.invest.detail', ['signal_id' => $signal->id]) }}"
                                        class="btn btn-sm"
                                        style="background: linear-gradient(135deg, #f5a623 0%, #f7b733 100%); 
                                          color: white; font-size: 11px; padding: 4px 12px;">
                                        <i class="bi bi-eye me-1"></i>View Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center">
                        <i class="bi bi-broadcast-pin text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-3 mb-0">No open signals for {{ $coinInfo['name'] }}</p>
                        <small class="text-muted">Check back later for new trading signals</small>
                    </div>
                @endforelse
            </div>

            <div class="card-dark shadow-sm p-3 mb-3">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle-fill text-gold" style="font-size: 18px; margin-top: 2px;"></i>
                    <div>
                        <h6 class="text-white mb-1" style="font-size: 13px;">How It Works</h6>
                        <ul class="small text-muted mb-0 ps-3" style="font-size: 12px;">
                            <li>View signal details before joining</li>
                            <li>Bet amount is 1% of your Trade Balance</li>
                            <li>Minimum $ 100.00 available balance required</li>
                            <li>Your bet will be locked until settlement</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // Timeframe switcher untuk TradingView
            document.querySelectorAll('.timeframe-pill').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.timeframe-pill').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const interval = this.getAttribute('data-interval');
                    const iframe = document.getElementById('tradingViewChart');
                    const currentSrc = iframe.src;
                    const newSrc = currentSrc.replace(/interval=\w+/, 'interval=' + interval);
                    iframe.src = newSrc;
                });
            });

            // Auto hide alerts
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        </script>
    @endpush
@endsection
