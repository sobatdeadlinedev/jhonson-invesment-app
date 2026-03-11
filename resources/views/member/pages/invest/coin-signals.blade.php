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

            {{-- ── BACK BUTTON ──────────────────────────────────── --}}
            <div class="mb-3">
                <a href="{{ route('member.invest.index') }}" style="display:inline-flex;align-items:center;gap:7px;background:#0d1120;border:1px solid #1a2235;border-radius:10px;padding:8px 14px;text-decoration:none;color:#7a8fad;font-size:13px;transition:color .15s;" onmouseover="this.style.color='#e2eaf8'" onmouseout="this.style.color='#7a8fad'">
                    <i class="bi bi-arrow-left"></i> Back to Coins
                </a>
            </div>

            {{-- ── COIN HEADER ──────────────────────────────────── --}}
            <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:16px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-30px;right:-30px;width:140px;height:140px;border-radius:50%;background:radial-gradient(circle,rgba(0,212,138,.08) 0%,transparent 65%);pointer-events:none;"></div>
                <div style="display:flex;align-items:center;gap:14px;position:relative;">
                    <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,{{ $coinInfo['color'] }} 0%,{{ $coinInfo['color'] }}bb 100%);display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;flex-shrink:0;">
                        <i class="{{ $coinInfo['icon'] }}"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:16px;font-weight:700;color:#e2eaf8;margin-bottom:2px;">{{ $coinInfo['symbol'] }}</div>
                        <div style="font-size:11px;color:#3a4d66;">{{ $coinInfo['name'] }}</div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-family:monospace;font-size:20px;font-weight:700;color:#f5a623;">{{ count($openSignals) }}</div>
                        <div style="font-size:10px;color:#3a4d66;">Open Signals</div>
                    </div>
                </div>
            </div>

            {{-- ── BALANCE SUMMARY ──────────────────────────────── --}}
            <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:16px;position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(0,212,138,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(0,212,138,.025) 1px,transparent 1px);background-size:28px 28px;"></div>
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

            {{-- ── INSUFFICIENT BALANCE NOTICE ─────────────────── --}}
            @if (!auth()->user()->canJoinSignal())
                <div class="mb-3" style="background:rgba(240,79,90,.08);border:1px solid rgba(240,79,90,.25);border-left:3px solid #f04f5a;border-radius:12px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f04f5a;font-size:14px;flex-shrink:0;margin-top:1px;"></i>
                    <div style="font-size:12px;color:#f04f5a;line-height:1.5;">
                        <strong>Notice:</strong> Minimum $100.00 available Trade Balance required to join signals.
                        Please <a href="{{ route('member.balance.transfer') }}" style="color:#e2eaf8;text-decoration:underline;">transfer funds</a> first.
                    </div>
                </div>
            @endif

            {{-- ── CHART ────────────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">{{ $coinInfo['name'] }} Chart</span>
                    <div style="display:flex;gap:4px;">
                        <button class="xc-tf active" data-interval="60">1H</button>
                        <button class="xc-tf" data-interval="D">1D</button>
                        <button class="xc-tf" data-interval="W">1W</button>
                    </div>
                </div>
                <div style="height:400px;padding:12px;">
                    <iframe id="tradingViewChart"
                        src="https://www.tradingview.com/widgetembed/?symbol={{ $coinInfo['tradingview_symbol'] }}&interval=60&theme=dark&style=1&locale=en&toolbar_bg=1d2058&hidesidetoolbar=1&hidetoptoolbar=1&symboledit=0&saveimage=0&withdateranges=0&allow_symbol_change=0&show_popup_button=0&details=0&calendar=0&studies=%5B%5D"
                        style="width:100%;height:100%;border:none;border-radius:10px;" frameborder="0" allowtransparency="true" scrolling="no">
                    </iframe>
                </div>
            </div>

            {{-- ── SIGNALS LIST ─────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Available Trading Signals</span>
                </div>

                @forelse($openSignals as $signal)
                    @php $isPending = $signal->result === 'pending' || $signal->result === null; @endphp

                    <div style="padding:14px 16px;border-bottom:1px solid rgba(26,34,53,.8);">
                        {{-- Signal Header --}}
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:center;gap:7px;margin-bottom:4px;">
                                    <i class="bi bi-broadcast" style="font-size:12px;color:#f5a623;"></i>
                                    <span style="font-size:14px;font-weight:700;color:#e2eaf8;">{{ $signal->title }}</span>
                                </div>
                                @if ($signal->description)
                                    <div style="font-size:11px;color:#3a4d66;line-height:1.4;">{{ Str::limit($signal->description, 60) }}</div>
                                @endif
                            </div>
                            <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.20);border-radius:99px;padding:3px 9px;font-size:10px;font-weight:700;color:#00d48a;flex-shrink:0;margin-left:8px;">
                                <span style="width:5px;height:5px;border-radius:50%;background:#00d48a;animation:xi-blink 1.6s infinite;"></span> OPEN
                            </span>
                        </div>

                        {{-- Prices --}}
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;">
                            <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:8px;padding:8px 10px;">
                                <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:3px;">Opening Price</div>
                                <div style="font-family:monospace;font-size:13px;font-weight:700;color:#e2eaf8;">
                                    @if ($isPending || !$signal->entry_price) ~ @else ${{ number_format($signal->entry_price, 2) }} @endif
                                </div>
                            </div>
                            <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:8px;padding:8px 10px;">
                                <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:3px;">Settlement Price</div>
                                <div style="font-family:monospace;font-size:13px;font-weight:700;color:#f5a623;">
                                    @if ($isPending || !$signal->target_price) ~ @else ${{ number_format($signal->target_price, 2) }} @endif
                                </div>
                            </div>
                        </div>

                        {{-- Action --}}
                        <div style="padding-top:10px;border-top:1px solid #1a2235;">
                            @if (in_array($signal->id, $joinedSignalIds))
                                <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(100,160,255,.10);border:1px solid rgba(100,160,255,.20);border-radius:8px;padding:6px 14px;font-size:12px;font-weight:700;color:#64a0ff;">
                                    <i class="bi bi-check-circle-fill"></i> Joined
                                </span>
                            @else
                                <a href="{{ route('member.invest.detail', ['signal_id' => $signal->id]) }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);border:none;border-radius:8px;padding:7px 16px;font-size:12px;font-weight:700;color:#080b12;text-decoration:none;">
                                    <i class="bi bi-eye"></i> View Detail
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="padding:40px 20px;text-align:center;">
                        <div style="width:60px;height:60px;border-radius:18px;background:rgba(255,255,255,.04);border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                            <i class="bi bi-broadcast-pin" style="font-size:26px;color:#3a4d66;"></i>
                        </div>
                        <div style="font-size:14px;color:#7a8fad;margin-bottom:5px;">No open signals for {{ $coinInfo['name'] }}</div>
                        <div style="font-size:12px;color:#3a4d66;">Check back later for new trading signals</div>
                    </div>
                @endforelse
            </div>

            {{-- ── INFO CARD ────────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-left:3px solid #f5a623;border-radius:14px;padding:14px 16px;display:flex;align-items:flex-start;gap:10px;">
                <i class="bi bi-info-circle-fill" style="font-size:16px;color:#f5a623;flex-shrink:0;margin-top:1px;"></i>
                <div>
                    <div style="font-size:12px;font-weight:700;color:#e2eaf8;margin-bottom:6px;">How It Works</div>
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        @foreach(['View signal details before joining','Bet amount is 1% of your Trade Balance','Minimum $100.00 available balance required','Your bet will be locked until settlement'] as $info)
                        <div style="display:flex;align-items:flex-start;gap:6px;">
                            <span style="width:4px;height:4px;border-radius:50%;background:#3a4d66;flex-shrink:0;margin-top:5px;"></span>
                            <span style="font-size:12px;color:#7a8fad;line-height:1.5;">{{ $info }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .xc-tf {
            background: rgba(255,255,255,.04);
            border: 1px solid #1a2235;
            border-radius: 7px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            color: #7a8fad;
            cursor: pointer;
            transition: all .15s;
        }
        .xc-tf.active {
            background: #f5a623;
            color: #080b12;
            border-color: #f5a623;
        }
        .xc-tf:hover:not(.active) { color: #e2eaf8; }
        @keyframes xi-blink { 0%,100%{opacity:1} 50%{opacity:.15} }
    </style>

    @push('scripts')
    <script>
        document.querySelectorAll('.xc-tf').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.xc-tf').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const interval = this.getAttribute('data-interval');
                const iframe = document.getElementById('tradingViewChart');
                iframe.src = iframe.src.replace(/interval=\w+/, 'interval=' + interval);
            });
        });
        setTimeout(function() { $('.alert').fadeOut('slow'); }, 5000);
    </script>
    @endpush
@endsection