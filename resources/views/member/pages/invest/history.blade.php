@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── BACK BUTTON ──────────────────────────────────── --}}
            <div class="mb-3">
                <a href="{{ route('member.invest.index') }}" style="display:inline-flex;align-items:center;gap:7px;background:#0d1120;border:1px solid #1a2235;border-radius:10px;padding:8px 14px;text-decoration:none;color:#7a8fad;font-size:13px;transition:all .15s;" onmouseover="this.style.color='#e2eaf8'" onmouseout="this.style.color='#7a8fad'">
                    <i class="bi bi-arrow-left" style="font-size:13px;"></i> Back to Signals
                </a>
            </div>

            {{-- ── STATS CARDS ──────────────────────────────────── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">

                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid #1a2235;border-radius:16px;padding:16px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:radial-gradient(circle,rgba(100,160,255,.10) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:6px;">Total Joined</div>
                    <div style="font-family:monospace;font-size:26px;font-weight:700;color:#e2eaf8;letter-spacing:-1px;line-height:1;">{{ $totalJoined }}</div>
                    <div style="font-size:11px;color:#3a4d66;margin-top:3px;">Signals</div>
                </div>

                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid #1a2235;border-radius:16px;padding:16px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:radial-gradient(circle,rgba({{ $winRate >= 50 ? '0,212,138' : '240,79,90' }},.10) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:6px;">Win Rate</div>
                    <div style="font-family:monospace;font-size:26px;font-weight:700;color:{{ $winRate >= 50 ? '#00d48a' : '#f04f5a' }};letter-spacing:-1px;line-height:1;">{{ number_format($winRate, 1) }}%</div>
                    <div style="font-size:11px;color:#3a4d66;margin-top:3px;">{{ $totalWins }}/{{ $totalSettled }} Wins</div>
                </div>

            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">

                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid #1a2235;border-radius:16px;padding:16px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:radial-gradient(circle,rgba({{ $totalProfitLoss >= 0 ? '0,212,138' : '240,79,90' }},.10) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:6px;">Total P/L</div>
                    <div style="font-family:monospace;font-size:18px;font-weight:700;color:{{ $totalProfitLoss >= 0 ? '#00d48a' : '#f04f5a' }};letter-spacing:-.5px;line-height:1;">{{ $totalProfitLoss >= 0 ? '+' : '' }}$&nbsp;{{ number_format($totalProfitLoss, 2) }}</div>
                    <div style="font-size:11px;color:#3a4d66;margin-top:3px;">Profit/Loss</div>
                </div>

                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid #1a2235;border-radius:16px;padding:16px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.10) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:6px;">Total Fees</div>
                    <div style="font-family:monospace;font-size:18px;font-weight:700;color:#f5a623;letter-spacing:-.5px;line-height:1;">$&nbsp;{{ number_format($totalFees, 2) }}</div>
                    <div style="font-size:11px;color:#3a4d66;margin-top:3px;">Trading Fees</div>
                </div>

            </div>

            {{-- ── HISTORY LIST ─────────────────────────────────── --}}
            <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:12px;">Trading History</div>

            @forelse($participants as $participant)
                @php
                    $signal = $participant->signal;
                    $coinInfo = $signal->getCoinInfo();
                    $isPending = $signal->status != 'settled' || $signal->result === null;
                    $isWin = $signal->result === 'win';
                    $isSettled = $participant->status === 'settled';
                    $profitLossAmount = $participant->profit_loss ?? 0;
                    $feeAmount = $participant->fee_amount ?? 0;
                    $netResult = $profitLossAmount - $feeAmount;
                    $isFinalProfit = $netResult > 0;

                    $direction = '';
                    $directionIcon = '';
                    $dirColor = '#7a8fad';
                    $userOutcome = '';

                    if ($isPending) {
                        $direction = 'PENDING';
                        $dirColor = '#f5a623';
                    } else {
                        $adminChoice = strtolower($signal->admin_choice ?? '');
                        if ($adminChoice === 'call') {
                            $direction = 'CALL';
                            $directionIcon = '↑';
                            $dirColor = '#00d48a';
                        } elseif ($adminChoice === 'put') {
                            $direction = 'PUT';
                            $directionIcon = '↓';
                            $dirColor = '#f04f5a';
                        } else {
                            $direction = 'N/A';
                        }
                        if ($isSettled) {
                            $userOutcome = $signal->result === 'win'
                                ? '<i class="bi bi-check-circle-fill" style="color:#00d48a;font-size:10px;margin-left:3px;"></i>'
                                : '<i class="bi bi-x-circle-fill" style="color:#f04f5a;font-size:10px;margin-left:3px;"></i>';
                        }
                    }

                    $resultColor = $isPending ? 'rgba(245,166,35,.12)' : ($isWin ? 'rgba(0,212,138,.07)' : 'rgba(240,79,90,.07)');
                    $resultBorder = $isPending ? '#f5a623' : ($isWin ? '#00d48a' : '#f04f5a');
                @endphp

                <div style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;overflow:hidden;margin-bottom:10px;">

                    {{-- Card Header --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border-bottom:1px solid #1a2235;background:{{ $resultColor }};">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="display:inline-flex;align-items:center;padding:4px 10px;border-radius:99px;font-size:11px;font-weight:700;letter-spacing:.4px;
                                background:{{ $isPending ? 'rgba(245,166,35,.15)' : ($isWin ? 'rgba(0,212,138,.15)' : 'rgba(240,79,90,.15)') }};
                                color:{{ $isPending ? '#f5a623' : ($isWin ? '#00d48a' : '#f04f5a') }};
                                border:1px solid {{ $isPending ? 'rgba(245,166,35,.25)' : ($isWin ? 'rgba(0,212,138,.25)' : 'rgba(240,79,90,.25)') }};">
                                {{ strtoupper($signal->title ?? 'SIGNAL') }}
                            </span>
                            <span style="font-size:13px;font-weight:700;color:#e2eaf8;">{{ $coinInfo['symbol'] }}</span>
                        </div>
                        <span style="font-size:12px;font-weight:700;color:{{ $dirColor }};">
                            {{ $direction }} {{ $directionIcon }} {!! $userOutcome !!}
                        </span>
                    </div>

                    {{-- Card Body --}}
                    <div style="padding:12px 14px;display:flex;flex-direction:column;gap:8px;">

                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#3a4d66;">time period</span>
                            <span style="font-size:12px;color:#e2eaf8;">
                                @if ($isPending) ~ @else
                                    {{ $signal->opened_at ? $signal->opened_at->format('H:i') : '-' }} –
                                    {{ $signal->closed_at ? $signal->closed_at->format('H:i') : '-' }}
                                @endif
                            </span>
                        </div>

                        @if (!$isPending && $isSettled && $feeAmount > 0)
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#3a4d66;">trading fee (1%)</span>
                            <span style="font-size:12px;color:#f5a623;font-family:monospace;">-{{ number_format($feeAmount, 2) }}</span>
                        </div>
                        @endif

                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#3a4d66;">net profit/loss</span>
                            <span style="font-size:12px;font-weight:700;font-family:monospace;color:{{ $isFinalProfit ? '#00d48a' : '#f04f5a' }};">
                                @if ($isPending) ~
                                @else {{ $isSettled ? ($netResult >= 0 ? '+' : '') . number_format($netResult, 2) : '-' }}
                                @endif
                            </span>
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#3a4d66;">rate of return</span>
                            <span style="font-size:12px;color:#e2eaf8;font-family:monospace;">
                                @if ($isPending) ~ @else {{ $isSettled ? number_format($signal->rate_of_return, 2) . '%' : '-' }} @endif
                            </span>
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#3a4d66;">order quantity</span>
                            <span style="font-size:12px;color:#e2eaf8;font-family:monospace;">{{ number_format($participant->bet_amount, 2) }}</span>
                        </div>

                        <div style="height:1px;background:#1a2235;"></div>

                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#3a4d66;">opening price</span>
                            <span style="font-size:12px;color:#e2eaf8;font-family:monospace;">
                                @if ($isPending) ~ @else {{ number_format($signal->entry_price, 3) }} @endif
                            </span>
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#3a4d66;">settlement price</span>
                            <span style="font-size:12px;color:#e2eaf8;font-family:monospace;">
                                @if ($isPending) ~ @else {{ $isSettled ? number_format($signal->target_price, 3) : '-' }} @endif
                            </span>
                        </div>

                        <div style="height:1px;background:#1a2235;"></div>

                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#3a4d66;">order time</span>
                            <span style="font-size:12px;color:#7a8fad;font-family:monospace;">{{ $participant->joined_at->format('Y-m-d H:i:s') }}</span>
                        </div>

                    </div>
                </div>

            @empty
                <div style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:40px 20px;text-align:center;">
                    <div style="width:60px;height:60px;border-radius:18px;background:rgba(255,255,255,.04);border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <i class="bi bi-clock-history" style="font-size:26px;color:#3a4d66;"></i>
                    </div>
                    <div style="font-size:14px;color:#7a8fad;margin-bottom:5px;">No trading history yet</div>
                    <div style="font-size:12px;color:#3a4d66;">Join signals to start trading</div>
                </div>
            @endforelse

            {{-- ── PAGINATION ───────────────────────────────────── --}}
            @if ($participants->hasPages())
                <div style="display:flex;justify-content:center;margin-bottom:12px;">
                    {{ $participants->links() }}
                </div>
            @endif

            {{-- ── INFO CARD ────────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-left:3px solid #f5a623;border-radius:14px;padding:14px 16px;display:flex;align-items:flex-start;gap:10px;">
                <i class="bi bi-info-circle-fill" style="font-size:16px;color:#f5a623;flex-shrink:0;margin-top:1px;"></i>
                <div>
                    <div style="font-size:12px;font-weight:700;color:#e2eaf8;margin-bottom:6px;">About Results</div>
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        @foreach([
                            'Trading Fee = 1% of your bet amount (deducted on win only)',
                        ] as $info)
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
@endsection