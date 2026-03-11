@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── BACK BUTTON ──────────────────────────────────── --}}
            <div style="margin-bottom:14px;">
                <a href="{{ route('member.profile.index') }}" style="display:inline-flex;align-items:center;gap:7px;background:#0d1120;border:1px solid #1a2235;border-radius:10px;padding:8px 14px;text-decoration:none;color:#7a8fad;font-size:13px;">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div style="font-size:18px;font-weight:700;color:#e2eaf8;margin-bottom:16px;">Withdrawal History</div>

            {{-- ── SUMMARY CARDS ────────────────────────────────── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid #1a2235;border-radius:16px;padding:14px 16px;display:flex;align-items:center;gap:12px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-15px;right:-15px;width:70px;height:70px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.12) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="width:38px;height:38px;border-radius:11px;background:rgba(245,166,35,.12);border:1px solid rgba(245,166,35,.20);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-clock-history" style="font-size:16px;color:#f5a623;"></i>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#3a4d66;letter-spacing:.4px;text-transform:uppercase;margin-bottom:3px;">Pending</div>
                        <div style="font-family:monospace;font-size:20px;font-weight:700;color:#f5a623;">{{ $pendingCount }}</div>
                    </div>
                </div>
                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid #1a2235;border-radius:16px;padding:14px 16px;display:flex;align-items:center;gap:12px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-15px;right:-15px;width:70px;height:70px;border-radius:50%;background:radial-gradient(circle,rgba(0,212,138,.10) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="width:38px;height:38px;border-radius:11px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-check-circle" style="font-size:16px;color:#00d48a;"></i>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#3a4d66;letter-spacing:.4px;text-transform:uppercase;margin-bottom:3px;">Completed</div>
                        <div style="font-family:monospace;font-size:20px;font-weight:700;color:#00d48a;">{{ $completedCount }}</div>
                    </div>
                </div>
            </div>

            {{-- ── FILTER TABS ──────────────────────────────────── --}}
            <div style="display:flex;gap:6px;overflow-x:auto;padding-bottom:4px;margin-bottom:12px;">
                @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected','completed'=>'Completed'] as $key=>$label)
                <button class="xwh-tab {{ $key === 'all' ? 'active' : '' }}" onclick="filterTransactions('{{ $key }}')"
                    style="padding:7px 14px;border-radius:8px;font-size:12px;font-weight:600;white-space:nowrap;cursor:pointer;flex-shrink:0;border:1px solid;
                    background:{{ $key === 'all' ? '#f5a623' : 'rgba(255,255,255,.04)' }};
                    border-color:{{ $key === 'all' ? '#f5a623' : '#1a2235' }};
                    color:{{ $key === 'all' ? '#080b12' : '#3a4d66' }};">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- ── TRANSACTION LIST ─────────────────────────────── --}}
            <div style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;margin-bottom:12px;">

                @forelse($transactions as $transaction)
                @php
                    $ic = match($transaction->status) {
                        'pending'   => ['bg'=>'rgba(245,166,35,.12)','br'=>'rgba(245,166,35,.22)','cl'=>'#f5a623'],
                        'approved'  => ['bg'=>'rgba(100,160,255,.12)','br'=>'rgba(100,160,255,.22)','cl'=>'#64a0ff'],
                        'completed' => ['bg'=>'rgba(0,212,138,.12)','br'=>'rgba(0,212,138,.22)','cl'=>'#00d48a'],
                        'cancelled' => ['bg'=>'rgba(120,130,150,.12)','br'=>'rgba(120,130,150,.22)','cl'=>'#6c7a8d'],
                        default     => ['bg'=>'rgba(240,79,90,.12)','br'=>'rgba(240,79,90,.22)','cl'=>'#f04f5a'],
                    };
                    $icon = match($transaction->status) {
                        'pending'  => 'bi-clock-history',
                        'approved' => 'bi-hourglass-split',
                        'completed'=> 'bi-check-circle',
                        'cancelled'=> 'bi-slash-circle',
                        default    => 'bi-x-circle',
                    };
                @endphp
                <div class="xwh-item" data-status="{{ $transaction->status }}"
                    style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(26,34,53,.8);transition:background .15s;"
                    onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background='transparent'">

                    <div style="width:40px;height:40px;border-radius:11px;background:{{ $ic['bg'] }};border:1px solid {{ $ic['br'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $icon }}" style="font-size:18px;color:{{ $ic['cl'] }};"></i>
                    </div>

                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#e2eaf8;">Withdrawal</div>
                                <div style="font-size:11px;color:#3a4d66;font-family:monospace;">{{ $transaction->reference }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:13px;font-weight:700;color:#f04f5a;font-family:monospace;">-{{ number_format($transaction->total_amount, 2) }} USDT</div>
                                <span style="display:inline-block;padding:2px 8px;border-radius:5px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.3px;
                                    background:{{ $ic['bg'] }};color:{{ $ic['cl'] }};border:1px solid {{ $ic['br'] }};">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Detail rows --}}
                        <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:8px;overflow:hidden;margin-bottom:{{ $transaction->status === 'pending' ? '8px' : '0' }};">
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;border-bottom:1px solid rgba(26,34,53,.6);">
                                <span style="font-size:11px;color:#3a4d66;">Wallet Account</span>
                                <span style="font-size:11px;font-weight:600;color:#e2eaf8;">{{ $transaction->wallet ? $transaction->wallet->account_name : 'N/A' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;border-bottom:1px solid rgba(26,34,53,.6);">
                                <span style="font-size:11px;color:#3a4d66;">Account Number</span>
                                <span style="font-size:11px;font-weight:600;color:#e2eaf8;font-family:monospace;word-break:break-all;text-align:right;max-width:65%;">{{ $transaction->wallet ? $transaction->wallet->account_number : 'N/A' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;border-bottom:1px solid rgba(26,34,53,.6);">
                                <span style="font-size:11px;color:#3a4d66;">Withdrawal Amount</span>
                                <span style="font-size:11px;font-weight:600;color:#e2eaf8;font-family:monospace;">{{ number_format($transaction->total_amount, 2) }} USDT</span>
                            </div>
                            @if ($transaction->withdrawal_fee > 0)
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;border-bottom:1px solid rgba(26,34,53,.6);">
                                <span style="font-size:11px;color:#3a4d66;">Fee (5%)</span>
                                <span style="font-size:11px;font-weight:600;color:#f04f5a;font-family:monospace;">-{{ number_format($transaction->withdrawal_fee, 2) }} USDT</span>
                            </div>
                            @endif
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;border-bottom:1px solid rgba(26,34,53,.6);">
                                <span style="font-size:11px;color:#3a4d66;">You Receive</span>
                                <span style="font-size:11px;font-weight:600;color:#00d48a;font-family:monospace;">{{ number_format($transaction->amount, 2) }} USDT</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;{{ $transaction->status === 'completed' && $transaction->updated_at ? 'border-bottom:1px solid rgba(26,34,53,.6);' : '' }}">
                                <span style="font-size:11px;color:#3a4d66;">Date</span>
                                <span style="font-size:11px;color:#7a8fad;">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if ($transaction->status === 'completed' && $transaction->updated_at)
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;">
                                <span style="font-size:11px;color:#3a4d66;">Completed At</span>
                                <span style="font-size:11px;color:#7a8fad;">{{ $transaction->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Cancel button for pending --}}
                        @if ($transaction->status === 'pending')
                            <button type="button" onclick="cancelWithdrawal('{{ $transaction->reference }}')"
                                style="width:100%;background:rgba(240,79,90,.08);border:1px solid rgba(240,79,90,.22);border-radius:8px;padding:9px;font-size:12px;font-weight:600;color:#f04f5a;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                                <i class="bi bi-x-circle"></i> Cancel Withdrawal
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                    <div style="padding:40px 20px;text-align:center;">
                        <div style="width:60px;height:60px;border-radius:18px;background:rgba(255,255,255,.04);border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                            <i class="bi bi-inbox" style="font-size:26px;color:#3a4d66;"></i>
                        </div>
                        <div style="font-size:14px;color:#7a8fad;">No withdrawal history</div>
                    </div>
                @endforelse
            </div>

            {{-- ── PAGINATION ───────────────────────────────────── --}}
            @if ($transactions->hasPages())
                <div style="display:flex;justify-content:center;margin-bottom:12px;">
                    {{ $transactions->links() }}
                </div>
            @endif

        </div>
    </div>

    <style>
        .xwh-tab.active { background:#f5a623 !important; border-color:#f5a623 !important; color:#080b12 !important; }
        .xwh-item:last-child { border-bottom:none !important; }
    </style>

    <script>
        function filterTransactions(status) {
            document.querySelectorAll('.xwh-tab').forEach(t => {
                t.classList.remove('active');
                t.style.background = 'rgba(255,255,255,.04)';
                t.style.borderColor = '#1a2235';
                t.style.color = '#3a4d66';
            });
            event.target.classList.add('active');
            event.target.style.background = '#f5a623';
            event.target.style.borderColor = '#f5a623';
            event.target.style.color = '#080b12';

            document.querySelectorAll('.xwh-item').forEach(item => {
                item.style.display = (status === 'all' || item.dataset.status === status) ? 'flex' : 'none';
            });
        }

        function cancelWithdrawal(reference) {
            if (confirm('Are you sure you want to cancel this withdrawal?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/member/withdraw/cancel/' + reference;
                const csrf = document.createElement('input');
                csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                const method = document.createElement('input');
                method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
                form.appendChild(csrf); form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        }

        @if (session('success')) alert('{{ session('success') }}'); @endif
        @if (session('error'))   alert('{{ session('error') }}');   @endif
    </script>
@endsection