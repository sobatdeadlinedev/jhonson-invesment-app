@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── BACK BUTTON ──────────────────────────────────── --}}
            <div class="mb-3">
                <a href="{{ route('member.profile.index') }}" style="display:inline-flex;align-items:center;gap:7px;background:#0d1120;border:1px solid #1a2235;border-radius:10px;padding:8px 14px;text-decoration:none;color:#7a8fad;font-size:13px;" onmouseover="this.style.color='#e2eaf8'" onmouseout="this.style.color='#7a8fad'">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div style="font-size:18px;font-weight:700;color:#e2eaf8;margin-bottom:16px;">Deposit History</div>

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
                <button class="xdh-tab {{ $key === 'all' ? 'active' : '' }}" onclick="filterTransactions('{{ $key }}')"
                    style="padding:7px 14px;border-radius:8px;font-size:12px;font-weight:600;white-space:nowrap;cursor:pointer;flex-shrink:0;border:1px solid;transition:all .15s;
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
                    $iconColor = match($transaction->status) {
                        'pending'   => ['bg'=>'rgba(245,166,35,.12)','br'=>'rgba(245,166,35,.22)','cl'=>'#f5a623'],
                        'approved'  => ['bg'=>'rgba(100,160,255,.12)','br'=>'rgba(100,160,255,.22)','cl'=>'#64a0ff'],
                        'completed' => ['bg'=>'rgba(0,212,138,.12)','br'=>'rgba(0,212,138,.22)','cl'=>'#00d48a'],
                        default     => ['bg'=>'rgba(240,79,90,.12)','br'=>'rgba(240,79,90,.22)','cl'=>'#f04f5a'],
                    };
                    $icon = match($transaction->status) {
                        'pending'  => 'bi-clock-history',
                        'approved' => 'bi-hourglass-split',
                        'completed'=> 'bi-check-circle',
                        default    => 'bi-x-circle',
                    };
                @endphp
                <div class="xdh-item" data-status="{{ $transaction->status }}" style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(26,34,53,.8);transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background='transparent'">

                    <div style="width:40px;height:40px;border-radius:11px;background:{{ $iconColor['bg'] }};border:1px solid {{ $iconColor['br'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $icon }}" style="font-size:18px;color:{{ $iconColor['cl'] }};"></i>
                    </div>

                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#e2eaf8;">Deposit</div>
                                <div style="font-size:11px;color:#3a4d66;font-family:monospace;">{{ $transaction->reference }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:13px;font-weight:700;color:#00d48a;font-family:monospace;">+{{ number_format($transaction->amount, 2) }} USDT</div>
                                <span style="display:inline-block;padding:2px 8px;border-radius:5px;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.3px;
                                    background:{{ $iconColor['bg'] }};color:{{ $iconColor['cl'] }};border:1px solid {{ $iconColor['br'] }};">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Detail rows --}}
                        <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:8px;overflow:hidden;">
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;border-bottom:1px solid rgba(26,34,53,.6);">
                                <span style="font-size:11px;color:#3a4d66;">Payment Method</span>
                                <span style="font-size:11px;font-weight:600;color:#e2eaf8;">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;border-bottom:1px solid rgba(26,34,53,.6);">
                                <span style="font-size:11px;color:#3a4d66;">Amount</span>
                                <span style="font-size:11px;font-weight:600;color:#00d48a;font-family:monospace;">{{ number_format($transaction->total_amount, 2) }} USDT</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;{{ in_array($transaction->status, ['approved','completed']) && $transaction->updated_at || $transaction->payment_proof ? 'border-bottom:1px solid rgba(26,34,53,.6);' : '' }}">
                                <span style="font-size:11px;color:#3a4d66;">Date</span>
                                <span style="font-size:11px;color:#7a8fad;">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if ($transaction->status === 'rejected' && $transaction->rejection_reason)
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:7px 10px;border-top:1px solid rgba(240,79,90,.15);background:rgba(240,79,90,.05);">
                                <span style="font-size:11px;color:#f04f5a;flex-shrink:0;margin-right:8px;">Rejection Reason</span>
                                <span style="font-size:11px;font-weight:600;color:#f04f5a;text-align:right;">{{ $transaction->rejection_reason }}</span>
                            </div>
                            @endif
                            @if (in_array($transaction->status, ['approved','completed']) && $transaction->updated_at)
                            <div style="display:flex;justify-content:space-between;padding:7px 10px;{{ $transaction->payment_proof ? 'border-bottom:1px solid rgba(26,34,53,.6);' : '' }}">
                                <span style="font-size:11px;color:#3a4d66;">Processed At</span>
                                <span style="font-size:11px;color:#7a8fad;">{{ $transaction->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                            @endif
                            @if ($transaction->payment_proof)
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 10px;">
                                <span style="font-size:11px;color:#3a4d66;">Payment Proof</span>
                                <button type="button" onclick="viewProof('{{ asset('storage/' . $transaction->payment_proof) }}')"
                                    style="background:rgba(100,160,255,.10);border:1px solid rgba(100,160,255,.20);border-radius:6px;padding:3px 10px;color:#64a0ff;font-size:11px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <div style="padding:40px 20px;text-align:center;">
                        <div style="width:60px;height:60px;border-radius:18px;background:rgba(255,255,255,.04);border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                            <i class="bi bi-inbox" style="font-size:26px;color:#3a4d66;"></i>
                        </div>
                        <div style="font-size:14px;color:#7a8fad;">No deposit history</div>
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

    {{-- ── MODAL PAYMENT PROOF ──────────────────────────── --}}
    <div class="modal fade" id="proofModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;">
                <div class="modal-header" style="border-bottom:1px solid #1a2235;padding:16px 18px;">
                    <h5 class="modal-title" style="color:#e2eaf8;font-size:15px;font-weight:700;">Payment Proof</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:16px;text-align:center;">
                    <img id="proofImage" src="" alt="Payment Proof" style="max-width:100%;border-radius:10px;">
                </div>
            </div>
        </div>
    </div>

    <style>
        .xdh-tab.active { background: #f5a623 !important; border-color: #f5a623 !important; color: #080b12 !important; }
        .xdh-item:last-child { border-bottom: none !important; }
    </style>

    <script>
        function filterTransactions(status) {
            document.querySelectorAll('.xdh-tab').forEach(t => {
                t.classList.remove('active');
                t.style.background = 'rgba(255,255,255,.04)';
                t.style.borderColor = '#1a2235';
                t.style.color = '#3a4d66';
            });
            event.target.classList.add('active');
            event.target.style.background = '#f5a623';
            event.target.style.borderColor = '#f5a623';
            event.target.style.color = '#080b12';

            document.querySelectorAll('.xdh-item').forEach(item => {
                item.style.display = (status === 'all' || item.dataset.status === status) ? 'flex' : 'none';
            });
        }

        function viewProof(url) {
            document.getElementById('proofImage').src = url;
            new bootstrap.Modal(document.getElementById('proofModal')).show();
        }

        @if (session('success')) alert('{{ session('success') }}'); @endif
        @if (session('error'))   alert('{{ session('error') }}');   @endif
    </script>
@endsection