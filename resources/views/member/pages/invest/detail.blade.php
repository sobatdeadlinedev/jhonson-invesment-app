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
                    <i class="bi bi-arrow-left"></i> Back to Signals
                </a>
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

            @php $isPending = $signal->result === 'pending' || $signal->result === null; @endphp

            {{-- ── SIGNAL HEADER CARD ───────────────────────────── --}}
            <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:16px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-25px;right:-25px;width:120px;height:120px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.10) 0%,transparent 65%);pointer-events:none;"></div>

                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;position:relative;">
                    <div style="width:44px;height:44px;border-radius:13px;background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-broadcast" style="font-size:20px;color:#fff;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:15px;font-weight:700;color:#e2eaf8;margin-bottom:2px;">{{ $signal->title }}</div>
                        <div style="font-size:11px;color:#3a4d66;">Trading Signal</div>
                    </div>
                    @if ($signal->status === 'open')
                        <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.20);border-radius:99px;padding:4px 10px;font-size:10px;font-weight:700;color:#00d48a;flex-shrink:0;">
                            <span style="width:5px;height:5px;border-radius:50%;background:#00d48a;animation:xi-blink 1.6s infinite;"></span> OPEN
                        </span>
                    @elseif($signal->status === 'closed')
                        <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);border-radius:99px;padding:4px 10px;font-size:10px;font-weight:700;color:#f5a623;flex-shrink:0;">CLOSED</span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(122,143,173,.10);border:1px solid rgba(122,143,173,.20);border-radius:99px;padding:4px 10px;font-size:10px;font-weight:700;color:#7a8fad;flex-shrink:0;">SETTLED</span>
                    @endif
                </div>

                @if ($signal->description)
                    <div style="font-size:12px;color:#7a8fad;margin-bottom:14px;line-height:1.5;">{{ $signal->description }}</div>
                @endif

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 12px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Opening Price</div>
                        <div style="font-family:monospace;font-size:14px;font-weight:700;color:#f5a623;">
                            @if ($isPending || !$signal->entry_price) ~ @else ${{ number_format($signal->entry_price, 2) }} @endif
                        </div>
                    </div>
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 12px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Settlement Price</div>
                        <div style="font-family:monospace;font-size:14px;font-weight:700;color:#00d48a;">
                            @if ($isPending || !$signal->target_price) ~ @else ${{ number_format($signal->target_price, 2) }} @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── CHART ────────────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">{{ $signal->getCoinInfo()['name'] }} Chart</span>
                    <div style="display:flex;gap:4px;">
                        <button class="xc-tf active" data-interval="60">1H</button>
                        <button class="xc-tf" data-interval="D">1D</button>
                        <button class="xc-tf" data-interval="W">1W</button>
                    </div>
                </div>
                <div style="height:400px;padding:12px;">
                    <iframe id="tradingViewChart"
                        src="https://www.tradingview.com/widgetembed/?symbol={{ $signal->getTradingViewSymbol() }}&interval=60&theme=dark&style=1&locale=en&toolbar_bg=1d2058&hidesidetoolbar=1&hidetoptoolbar=1&symboledit=0&saveimage=0&withdateranges=0&hide_legend=0&allow_symbol_change=0&details=0&calendar=0&show_popup_button=0&studies=%5B%5D"
                        style="width:100%;height:100%;border:none;border-radius:10px;" frameborder="0" allowtransparency="true" scrolling="no">
                    </iframe>
                </div>
            </div>

            {{-- ── YOUR PARTICIPATION ───────────────────────────── --}}
            @if ($hasJoined)
                <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                    <div style="display:flex;align-items:center;gap:8px;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                        <i class="bi bi-check-circle-fill" style="color:#00d48a;font-size:14px;"></i>
                        <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Your Participation</span>
                    </div>
                    <div style="padding:14px 16px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <div style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Bet Amount</div>
                            <div style="font-family:monospace;font-size:14px;font-weight:700;color:#e2eaf8;">$&nbsp;{{ number_format($participant->bet_amount, 2) }}</div>
                        </div>
                        <div>
                            <div style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Status</div>
                            @if ($participant->status === 'joined')
                                <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);border-radius:8px;padding:4px 10px;font-size:11px;font-weight:700;color:#f5a623;">
                                    <i class="bi bi-clock"></i> Waiting
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.20);border-radius:8px;padding:4px 10px;font-size:11px;font-weight:700;color:#00d48a;">
                                    <i class="bi bi-check-circle"></i> Settled
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($participant->status === 'settled')
                        <div style="margin:0 16px 14px;padding-top:12px;border-top:1px solid #1a2235;">
                            <div style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Your Reward</div>
                            <div style="font-family:monospace;font-size:18px;font-weight:700;color:#00d48a;">+$&nbsp;{{ number_format($participant->profit_loss, 2) }}</div>
                            <div style="font-size:11px;color:#3a4d66;margin-top:2px;">You received {{ number_format(($participant->profit_loss / $participant->bet_amount) * 100, 2) }}% reward</div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ── BET CALCULATION ──────────────────────────────── --}}
            @if (!$hasJoined && $signal->status === 'open')
                <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                    <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                        <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Your Bet Calculation</span>
                    </div>
                    <div style="padding:14px 16px;display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 12px;">
                            <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Trade Balance</div>
                            <div style="font-family:monospace;font-size:14px;font-weight:700;color:#e2eaf8;">$&nbsp;{{ number_format(auth()->user()->trade_balance, 2) }}</div>
                        </div>
                        <div style="background:rgba(245,166,35,.06);border:1px solid rgba(245,166,35,.15);border-radius:10px;padding:10px 12px;">
                            <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Your Bet Amount</div>
                            <div style="font-family:monospace;font-size:14px;font-weight:700;color:#f5a623;">$&nbsp;{{ number_format($betAmountPreview, 2) }}</div>
                        </div>
                    </div>
                    @if ($signal->bet_type != 'percentage')
                        <div style="margin:0 16px 14px;background:rgba(100,160,255,.07);border:1px solid rgba(100,160,255,.18);border-radius:10px;padding:10px 12px;font-size:12px;color:#64a0ff;">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Fixed Bet:</strong> All participants bet exactly {{ number_format($signal->bet_value, 2) }} USDT regardless of balance
                        </div>
                    @endif
                </div>
            @endif

            {{-- ── SIGNAL STATISTICS ────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Signal Statistics</span>
                </div>
                <div style="padding:14px 16px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <span style="font-size:12px;color:#3a4d66;">Opened</span>
                        <span style="font-size:12px;font-family:monospace;color:#e2eaf8;">
                            @if ($signal->opened_at) {{ $signal->opened_at->format('H:i') }} @else ~ @endif
                        </span>
                    </div>

                    @if ($signal->status !== 'open')
                        <div style="border-top:1px solid #1a2235;padding-top:10px;display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div>
                                <div style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:6px;">Result</div>
                                @if ($signal->result === 'win')
                                    <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.20);border-radius:8px;padding:4px 10px;font-size:11px;font-weight:700;color:#00d48a;">
                                        <i class="bi bi-arrow-up"></i> WIN (CALL)
                                    </span>
                                @elseif($signal->result === 'loss')
                                    <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(240,79,90,.10);border:1px solid rgba(240,79,90,.20);border-radius:8px;padding:4px 10px;font-size:11px;font-weight:700;color:#f04f5a;">
                                        <i class="bi bi-arrow-down"></i> LOSS (PUT)
                                    </span>
                                @else
                                    <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);border-radius:8px;padding:4px 10px;font-size:11px;font-weight:700;color:#f5a623;">
                                        <i class="bi bi-clock"></i> PENDING
                                    </span>
                                @endif
                            </div>
                            <div>
                                <div style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:6px;">Win Rate</div>
                                <div style="font-family:monospace;font-size:16px;font-weight:700;color:#f5a623;">
                                    @if ($isPending || !$signal->rate_of_return) ~ @else {{ number_format($signal->rate_of_return, 2) }}% @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── JOIN BUTTON / ALERTS ─────────────────────────── --}}
            @if (!$hasJoined && $signal->status === 'open')
                @php
                    $hasSufficientBalance = $signal->bet_type == 'percentage'
                        ? auth()->user()->canJoinSignal()
                        : auth()->user()->getAvailableTradeBalance() >= $betAmountPreview;
                @endphp

                @if ($hasSufficientBalance)
                    <form action="{{ route('member.signals.join', $signal->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit"
                            style="width:100%;background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);border:none;border-radius:14px;padding:15px;font-size:14px;font-weight:700;color:#080b12;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 20px rgba(245,166,35,.30);"
                            onclick="return confirm('Join this signal?\n\nYour bet: ${{ number_format($betAmountPreview, 2) }} will be locked until settlement.\n\nDo you want to continue?')">
                            <i class="bi bi-check-circle-fill"></i> JOIN THIS SIGNAL
                        </button>
                    </form>
                @else
                    <div class="mb-3" style="background:rgba(240,79,90,.08);border:1px solid rgba(240,79,90,.25);border-left:3px solid #f04f5a;border-radius:12px;padding:12px 14px;font-size:12px;color:#f04f5a;line-height:1.5;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Insufficient Balance:</strong>
                        @if ($signal->bet_type == 'percentage') Minimum $100.00 available Trade Balance required.
                        @else You need at least ${{ number_format($betAmountPreview, 2) }} available Trade Balance. @endif
                        <a href="{{ route('member.balance.transfer') }}" style="color:#e2eaf8;text-decoration:underline;">Transfer funds now</a>
                    </div>
                @endif
            @elseif(!$hasJoined)
                <div class="mb-3" style="background:rgba(122,143,173,.07);border:1px solid rgba(122,143,173,.18);border-radius:12px;padding:12px 14px;font-size:12px;color:#7a8fad;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-lock-fill"></i> This signal is no longer available for joining.
                </div>
            @endif

            {{-- ── INFO CARD ────────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-left:3px solid #f5a623;border-radius:14px;padding:14px 16px;display:flex;align-items:flex-start;gap:10px;">
                <i class="bi bi-info-circle-fill" style="font-size:16px;color:#f5a623;flex-shrink:0;margin-top:1px;"></i>
                <div>
                    <div style="font-size:12px;font-weight:700;color:#e2eaf8;margin-bottom:6px;">Important Information</div>
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        @php
                        $infos = [
                            'Bet amount is determined by signal configuration (percentage or fixed)',
                            $signal->bet_type == 'percentage'
                                ? 'This signal uses '.number_format($signal->bet_value, 2).'% of your Trade Balance'
                                : 'This signal uses a fixed amount of '.number_format($signal->bet_value, 2).' USDT',
                            'Minimum $100.00 available balance required (for percentage-based signals)',
                            'Your bet will be locked until signal settlement',
                        ];
                        if (!$signal->is_public) array_splice($infos, 2, 0, ['Private Signal: Only selected users can access']);
                        @endphp
                        @foreach($infos as $info)
                        <div style="display:flex;align-items:flex-start;gap:6px;">
                            <span style="width:4px;height:4px;border-radius:50%;background:#3a4d66;flex-shrink:0;margin-top:5px;"></span>
                            <span style="font-size:12px;color:#7a8fad;line-height:1.5;">{!! $info !!}</span>
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
        .xc-tf.active { background: #f5a623; color: #080b12; border-color: #f5a623; }
        .xc-tf:hover:not(.active) { color: #e2eaf8; }
        @keyframes xi-blink { 0%,100%{opacity:1} 50%{opacity:.15} }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
@endsection