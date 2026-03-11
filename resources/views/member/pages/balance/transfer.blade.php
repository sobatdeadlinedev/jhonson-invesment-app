@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── TOP BAR ── --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <div style="font-size:11px;color:#3a4d66;letter-spacing:.6px;margin-bottom:3px;">Manajemen Dana</div>
                    <h5 class="mb-0 fw-bold" style="letter-spacing:-.4px;color:#e2eaf8;">
                        Transfer <span style="color:#f5a623;">Balance</span>
                    </h5>
                </div>
                <div style="width:38px;height:38px;border-radius:11px;background:#0d1120;border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;font-size:15px;color:#7a8fad;flex-shrink:0;">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>

            {{-- ── ALERTS ── --}}
            @if (session('success'))
                <div class="mb-3" style="background:rgba(0,212,138,.08);border:1px solid rgba(0,212,138,.25);border-left:3px solid #00d48a;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-check-circle-fill" style="color:#00d48a;font-size:14px;flex-shrink:0;"></i>
                    <span style="font-size:12px;color:#00d48a;">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-3" style="background:rgba(240,79,90,.08);border:1px solid rgba(240,79,90,.25);border-left:3px solid #f04f5a;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f04f5a;font-size:14px;flex-shrink:0;"></i>
                    <span style="font-size:12px;color:#f04f5a;">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ── BALANCE CARD ── --}}
            <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:20px;position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(0,212,138,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(0,212,138,.03) 1px,transparent 1px);background-size:28px 28px;"></div>
                <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.10) 0%,transparent 65%);pointer-events:none;"></div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <div style="display:flex;align-items:center;gap:5px;">
                        <span style="font-size:12px;color:#7a8fad;letter-spacing:.3px;">Exchange Balance</span>
                        <i class="bi bi-eye" style="font-size:11px;color:#3a4d66;cursor:pointer;"></i>
                    </div>
                    <span style="font-size:12px;color:#7a8fad;letter-spacing:.5px;">USDT</span>
                </div>

                <div style="font-family:monospace;font-size:36px;font-weight:700;color:#f5a623;letter-spacing:-1px;line-height:1;margin-bottom:4px;">
                    {{ number_format($exchangeBalance, 2) }}
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <span style="font-size:12px;color:#3a4d66;">≈${{ number_format($exchangeBalance, 2) }}</span>
                    <span style="font-size:12px;color:#3a4d66;">For Withdrawal</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Trade</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#e2eaf8;">$&nbsp;{{ number_format($tradeBalance, 2) }}</div>
                    </div>
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Available</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#00d48a;">$&nbsp;{{ number_format($availableTradeBalance, 2) }}</div>
                    </div>
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Locked</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#f5a623;">$&nbsp;{{ number_format($lockedBalance, 2) }}</div>
                    </div>
                </div>
            </div>

            {{-- ── VOLUME PROGRESS ── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:28px;height:28px;border-radius:8px;background:rgba(0,212,138,.07);border:1px solid rgba(0,212,138,.18);display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-bar-chart-line" style="font-size:12px;color:#00d48a;"></i>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Trading Volume Progress</span>
                    </div>
                    <span style="font-size:11px;font-family:monospace;font-weight:700;color:#00d48a;">{{ number_format($volumePercentage, 1) }}%</span>
                </div>
                <div style="padding:16px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span style="font-size:11px;color:#7a8fad;">Target</span>
                        <span style="font-size:12px;font-family:monospace;font-weight:700;color:#e2eaf8;">$&nbsp;{{ number_format($targetVolume, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span style="font-size:11px;color:#7a8fad;">Achieved</span>
                        <span style="font-size:12px;font-family:monospace;font-weight:700;color:#00d48a;">$&nbsp;{{ number_format($achievedVolume, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                        <span style="font-size:11px;color:#7a8fad;">Remaining</span>
                        <span style="font-size:12px;font-family:monospace;font-weight:700;color:#f5a623;">$&nbsp;{{ number_format($remainingVolume, 2) }}</span>
                    </div>
                    <div style="height:7px;background:#1a2235;border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:{{ min($volumePercentage, 100) }}%;background:linear-gradient(90deg,#00b374,#00d48a);border-radius:99px;transition:width .4s ease;"></div>
                    </div>
                    <div style="text-align:center;margin-top:8px;">
                        <span style="font-size:11px;color:#3a4d66;">{{ number_format($volumePercentage, 2) }}% Completed</span>
                    </div>
                </div>
            </div>

            {{-- ── TRANSFER TO TRADE ── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;gap:8px;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <div style="width:28px;height:28px;border-radius:8px;background:rgba(0,212,138,.07);border:1px solid rgba(0,212,138,.18);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-arrow-right" style="font-size:12px;color:#00d48a;"></i>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Transfer to Trade Balance</span>
                </div>
                <div style="padding:16px;">
                    <form action="{{ route('member.balance.transfer.to-trade') }}" method="POST">
                        @csrf
                        <div style="margin-bottom:12px;">
                            <label style="font-size:11px;color:#7a8fad;letter-spacing:.4px;text-transform:uppercase;display:block;margin-bottom:8px;">Amount (USDT)</label>
                            <input type="number" name="amount" class="form-control-dark" placeholder="Enter amount" min="10" step="0.01" required>
                            <div style="font-size:11px;color:#3a4d66;margin-top:5px;">Minimum: $10.00</div>
                        </div>
                        <div style="background:rgba(245,166,35,.07);border:1px solid rgba(245,166,35,.18);border-left:3px solid #f5a623;border-radius:12px;padding:10px 12px;display:flex;align-items:flex-start;gap:8px;margin-bottom:14px;">
                            <i class="bi bi-info-circle-fill" style="color:#f5a623;font-size:13px;flex-shrink:0;margin-top:1px;"></i>
                            <span style="font-size:11px;color:#f5a623;line-height:1.5;">Transfer ini akan menambah target volume trading sebesar jumlah yang ditransfer.</span>
                        </div>
                        <button type="submit" class="btn-call w-100" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;">
                            <i class="bi bi-arrow-right"></i> Transfer to Trade
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── TRANSFER TO EXCHANGE ── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;gap:8px;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <div style="width:28px;height:28px;border-radius:8px;background:rgba(240,79,90,.08);border:1px solid rgba(240,79,90,.2);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-arrow-left" style="font-size:12px;color:#f04f5a;"></i>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Transfer to Exchange Balance</span>
                </div>
                <div style="padding:16px;">
                    <form action="{{ route('member.balance.transfer.to-exchange') }}" method="POST" onsubmit="return confirmTransferToExchange()">
                        @csrf
                        <div style="margin-bottom:12px;">
                            <label style="font-size:11px;color:#7a8fad;letter-spacing:.4px;text-transform:uppercase;display:block;margin-bottom:8px;">Amount (USDT)</label>
                            <input type="number" name="amount" id="transferAmount" class="form-control-dark"
                                placeholder="Enter amount" min="10" step="0.01" max="{{ $availableTradeBalance }}" required>
                            <div style="font-size:11px;color:#3a4d66;margin-top:5px;">
                                Available: <span style="color:#00d48a;font-family:monospace;">$&nbsp;{{ number_format($availableTradeBalance, 2) }}</span>
                            </div>
                        </div>
                        @if ($needsPenalty)
                            <div style="background:rgba(240,79,90,.08);border:1px solid rgba(240,79,90,.2);border-left:3px solid #f04f5a;border-radius:12px;padding:10px 12px;display:flex;align-items:flex-start;gap:8px;margin-bottom:14px;">
                                <i class="bi bi-exclamation-triangle-fill" style="color:#f04f5a;font-size:13px;flex-shrink:0;margin-top:1px;"></i>
                                <span style="font-size:11px;color:#f04f5a;line-height:1.5;"><strong>Warning:</strong> Volume trading belum selesai. Penalti <strong>20%</strong> akan dikenakan pada transfer ini.</span>
                            </div>
                        @endif
                        <button type="submit" class="btn-put w-100" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;">
                            <i class="bi bi-arrow-left"></i> Transfer to Exchange
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── INFO CARD ── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;gap:8px;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <div style="width:28px;height:28px;border-radius:8px;background:rgba(245,166,35,.07);border:1px solid rgba(245,166,35,.18);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-info-circle" style="font-size:12px;color:#f5a623;"></i>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Important Information</span>
                </div>
                <div style="padding:14px 16px;">
                    @foreach([
                        ['icon'=>'bi-wallet2',            'color'=>'#f5a623', 'text'=>'Exchange Balance untuk deposit, withdrawal, dan komisi'],
                        ['icon'=>'bi-graph-up',            'color'=>'#00d48a', 'text'=>'Trade Balance digunakan untuk aktivitas trading'],
                        ['icon'=>'bi-arrow-right-circle',  'color'=>'#00d48a', 'text'=>'Transfer ke Trade Balance menambah kewajiban volume trading'],
                        ['icon'=>'bi-exclamation-triangle','color'=>'#f04f5a', 'text'=>'Penalti 20% berlaku jika target volume belum terpenuhi saat transfer balik'],
                        ['icon'=>'bi-check-circle',        'color'=>'#00d48a', 'text'=>'Selesaikan volume trading untuk menghindari penalti'],
                    ] as $i => $info)
                        <div style="display:flex;align-items:flex-start;gap:10px;padding:8px 0;{{ $i < 4 ? 'border-bottom:1px solid #1a2235;' : '' }}">
                            <i class="bi {{ $info['icon'] }}" style="font-size:13px;color:{{ $info['color'] }};flex-shrink:0;margin-top:1px;"></i>
                            <span style="font-size:12px;color:#7a8fad;line-height:1.5;">{{ $info['text'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function confirmTransferToExchange() {
                const amount = document.getElementById('transferAmount').value;
                @if ($needsPenalty)
                    const penalty = amount * 0.20;
                    const net = amount - penalty;
                    return confirm(
                        `WARNING: Penalti 20% akan diterapkan!\n\n` +
                        `Jumlah Transfer: $${parseFloat(amount).toFixed(2)}\n` +
                        `Penalti (20%): $${penalty.toFixed(2)}\n` +
                        `Yang diterima: $${net.toFixed(2)}\n\n` +
                        `Lanjutkan?`
                    );
                @else
                    return confirm(`Transfer $${parseFloat(amount).toFixed(2)} ke Exchange Balance?`);
                @endif
            }
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(el => el.style.display = 'none');
            }, 5000);
        </script>
    @endpush
@endsection