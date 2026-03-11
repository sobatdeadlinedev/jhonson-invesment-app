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

            {{-- ── CARD 1: DATA DIRI ────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Data Diri</span>
                    <a href="{{ route('member.verification.index') }}" style="display:inline-flex;align-items:center;gap:5px;background:rgba(0,212,138,.08);border:1px solid rgba(0,212,138,.18);border-radius:8px;padding:5px 12px;font-size:11px;font-weight:700;color:#00d48a;text-decoration:none;">
                        <i class="bi bi-shield-check" style="font-size:11px;"></i> Verifikasi Akun
                    </a>
                </div>
                <div style="padding:16px;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:50px;height:50px;border-radius:15px;background:#0a1118;border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-person-circle" style="font-size:26px;color:#3a4d66;"></i>
                        </div>
                        <div>
                            <div style="font-size:15px;font-weight:700;color:#e2eaf8;margin-bottom:4px;">{{ $user->name }}</div>
                            <div style="display:flex;align-items:center;gap:5px;">
                                <i class="bi bi-telephone-fill" style="font-size:11px;color:#f5a623;"></i>
                                <span style="font-size:12px;color:#7a8fad;">{{ $user->phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── CARD 2: BALANCE ──────────────────────────────── --}}
            <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:18px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-30px;right:-30px;width:150px;height:150px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.10) 0%,transparent 65%);pointer-events:none;"></div>
                <div style="position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(0,212,138,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(0,212,138,.025) 1px,transparent 1px);background-size:28px 28px;"></div>

                {{-- Total Balance --}}
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;position:relative;">
                    <div>
                        <div style="font-size:10px;color:#3a4d66;letter-spacing:.8px;text-transform:uppercase;margin-bottom:6px;">Total Balance</div>
                        <div style="font-family:monospace;font-size:28px;font-weight:700;color:#f5a623;letter-spacing:-1px;line-height:1;">{{ number_format($balanceBreakdown['total_balance'], 2) }}</div>
                        <div style="font-size:11px;color:#3a4d66;margin-top:3px;">USDT · Exchange + Trade</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:12px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-wallet2" style="font-size:17px;color:#f5a623;"></i>
                    </div>
                </div>

                {{-- Current Balance Breakdown --}}
                <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:12px;padding:12px 14px;margin-bottom:10px;position:relative;">
                    <div style="font-size:9px;color:#3a4d66;letter-spacing:.8px;text-transform:uppercase;margin-bottom:10px;display:flex;align-items:center;gap:5px;">
                        <i class="bi bi-wallet" style="font-size:10px;"></i> Current Balance
                    </div>
                    <div style="display:flex;flex-direction:column;gap:7px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;padding-left:6px;">Exchange Balance</span>
                            <span style="font-size:12px;font-weight:700;color:#e2eaf8;font-family:monospace;">{{ number_format($balanceBreakdown['exchange_balance'], 2) }} USDT</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;padding-left:6px;">Trade Balance</span>
                            <span style="font-size:12px;font-weight:700;color:#e2eaf8;font-family:monospace;">{{ number_format($balanceBreakdown['trade_balance'], 2) }} USDT</span>
                        </div>
                        @if ($balanceBreakdown['locked_balance'] > 0)
                            <div style="border-top:1px solid #1a2235;padding-top:7px;margin-top:2px;display:flex;flex-direction:column;gap:7px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:12px;color:#7a8fad;padding-left:14px;">└ Locked (Trading)</span>
                                    <span style="font-size:12px;font-weight:700;color:#f5a623;font-family:monospace;">-{{ number_format($balanceBreakdown['locked_balance'], 2) }} USDT</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:12px;color:#7a8fad;padding-left:14px;">└ Available</span>
                                    <span style="font-size:12px;font-weight:700;color:#00d48a;font-family:monospace;">{{ number_format($balanceBreakdown['available_trade_balance'], 2) }} USDT</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Transaction Summary --}}
                @if (isset($balanceBreakdown))
                <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:12px;padding:12px 14px;margin-bottom:10px;position:relative;">

                    {{-- Income --}}
                    <div style="font-size:9px;color:#3a4d66;letter-spacing:.8px;text-transform:uppercase;margin-bottom:10px;display:flex;align-items:center;gap:5px;">
                        <i class="bi bi-arrow-down-circle" style="font-size:10px;color:#00d48a;"></i> Total Income
                    </div>
                    <div style="display:flex;flex-direction:column;gap:7px;margin-bottom:12px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;padding-left:6px;">Deposits</span>
                            <span style="font-size:12px;font-weight:700;color:#00d48a;font-family:monospace;">+{{ number_format($balanceBreakdown['total_deposits'], 2) }} USDT</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;padding-left:6px;">Commissions</span>
                            <span style="font-size:12px;font-weight:700;color:#00d48a;font-family:monospace;">+{{ number_format($balanceBreakdown['total_commissions'], 2) }} USDT</span>
                        </div>
                    </div>

                    <div style="border-top:1px dashed rgba(255,255,255,.07);margin-bottom:12px;"></div>

                    {{-- Expenses --}}
                    <div style="font-size:9px;color:#3a4d66;letter-spacing:.8px;text-transform:uppercase;margin-bottom:10px;display:flex;align-items:center;gap:5px;">
                        <i class="bi bi-arrow-up-circle" style="font-size:10px;color:#f04f5a;"></i> Total Expenses
                    </div>
                    <div style="display:flex;flex-direction:column;gap:7px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;padding-left:6px;">Withdrawals (Net)</span>
                            <span style="font-size:12px;font-weight:700;color:#f04f5a;font-family:monospace;">-{{ number_format($balanceBreakdown['total_withdrawals_net'], 2) }} USDT</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;padding-left:6px;">Withdrawal Fees</span>
                            <span style="font-size:12px;font-weight:700;color:#f04f5a;font-family:monospace;">-{{ number_format($balanceBreakdown['total_withdrawal_fees'], 2) }} USDT</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Trading Volume --}}
                @if ($balanceBreakdown['target_volume'] > 0)
                @php $volumePct = $balanceBreakdown['target_volume'] > 0 ? ($balanceBreakdown['achieved_volume'] / $balanceBreakdown['target_volume']) * 100 : 0; @endphp
                <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:12px;padding:12px 14px;margin-bottom:10px;position:relative;">
                    <div style="font-size:9px;color:#3a4d66;letter-spacing:.8px;text-transform:uppercase;margin-bottom:10px;display:flex;align-items:center;gap:5px;">
                        <i class="bi bi-graph-up" style="font-size:10px;color:#9945ff;"></i> Trading Volume
                    </div>
                    <div style="display:flex;flex-direction:column;gap:7px;margin-bottom:12px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;">Target</span>
                            <span style="font-size:12px;font-weight:700;color:#e2eaf8;font-family:monospace;">{{ number_format($balanceBreakdown['target_volume'], 2) }} USDT</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;">Achieved</span>
                            <span style="font-size:12px;font-weight:700;color:#00d48a;font-family:monospace;">{{ number_format($balanceBreakdown['achieved_volume'], 2) }} USDT</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;color:#7a8fad;">Remaining</span>
                            <span style="font-size:12px;font-weight:700;color:#f5a623;font-family:monospace;">{{ number_format($balanceBreakdown['remaining_volume'], 2) }} USDT</span>
                        </div>
                    </div>
                    {{-- Progress Bar --}}
                    <div style="height:6px;background:rgba(255,255,255,.08);border-radius:99px;overflow:hidden;margin-bottom:6px;">
                        <div style="height:100%;width:{{ $volumePct }}%;background:linear-gradient(90deg,#9945ff,#da70d6);border-radius:99px;transition:width .4s;"></div>
                    </div>
                    <div style="font-size:10px;color:#3a4d66;text-align:center;">{{ number_format($volumePct, 1) }}% Completed</div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;position:relative;">
                    <a href="{{ route('member.deposit.index') }}" style="display:flex;align-items:center;justify-content:center;gap:6px;background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);border:none;border-radius:12px;padding:12px;font-size:13px;font-weight:700;color:#080b12;text-decoration:none;box-shadow:0 4px 14px rgba(245,166,35,.25);">
                        <i class="bi bi-plus-circle"></i> Deposit
                    </a>
                    <a href="{{ route('member.withdraw.index') }}" style="display:flex;align-items:center;justify-content:center;gap:6px;background:rgba(245,166,35,.08);border:1px solid rgba(245,166,35,.25);border-radius:12px;padding:12px;font-size:13px;font-weight:700;color:#f5a623;text-decoration:none;">
                        <i class="bi bi-arrow-up-circle"></i> Withdraw
                    </a>
                </div>
                <a href="{{ route('member.balance.transfer') }}" style="display:flex;align-items:center;justify-content:center;gap:6px;background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:12px;padding:11px;font-size:13px;font-weight:600;color:#7a8fad;text-decoration:none;position:relative;">
                    <i class="bi bi-arrow-left-right"></i> Transfer Balance
                </a>
            </div>

            {{-- ── CARD 3: TRANSACTION HISTORY ─────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Transaction History</span>
                </div>

                {{-- Tabs --}}
                <div style="display:flex;border-bottom:1px solid #1a2235;background:rgba(255,255,255,.02);">
                    @foreach([['deposit','Deposit','bi-arrow-down-circle',$deposits->count()],['withdrawal','Withdrawal','bi-arrow-up-circle',$withdrawals->count()],['commission','Commission','bi-gift',$commissions->count()]] as [$key,$label,$icon,$count])
                    <button class="xa-tab {{ $loop->first ? 'active' : '' }}" onclick="switchTransactionTab('{{ $key }}')" style="flex:1;padding:11px 6px;background:transparent;border:none;font-size:11px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;position:relative;color:{{ $loop->first ? '#f5a623' : '#3a4d66' }};">
                        <i class="bi {{ $icon }}" style="font-size:12px;"></i>
                        {{ $label }}
                        <span style="background:{{ $loop->first ? '#f5a623' : 'rgba(255,255,255,.06)' }};color:{{ $loop->first ? '#080b12' : '#3a4d66' }};border-radius:6px;padding:1px 6px;font-size:10px;font-weight:700;">{{ $count }}</span>
                        @if($loop->first)<span class="xa-tab-line" style="position:absolute;bottom:0;left:0;right:0;height:2px;background:#f5a623;"></span>@endif
                    </button>
                    @endforeach
                </div>

                {{-- Deposit List --}}
                <div id="deposit-list" class="xa-list active">
                    @forelse($deposits as $deposit)
                        <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(26,34,53,.8);">
                            <div style="width:38px;height:38px;border-radius:11px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-arrow-down-circle" style="font-size:17px;color:#00d48a;"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px;">
                                    <div>
                                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;">Deposit</div>
                                        <div style="font-size:11px;color:#3a4d66;font-family:monospace;">{{ $deposit->reference }}</div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div style="font-size:13px;font-weight:700;color:#00d48a;font-family:monospace;">+{{ number_format($deposit->amount, 2) }}</div>
                                        <span class="xa-status {{ $deposit->status }}">{{ ucfirst($deposit->status) }}</span>
                                    </div>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
                                    <span style="font-size:11px;color:#3a4d66;"><i class="bi bi-calendar3 me-1"></i>{{ $deposit->created_at->format('d M Y, H:i') }}</span>
                                    @if ($deposit->payment_method)
                                        <span style="font-size:11px;color:#f5a623;"><i class="bi bi-credit-card me-1"></i>{{ ucfirst(str_replace('_', ' ', $deposit->payment_method)) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:36px 20px;text-align:center;">
                            <i class="bi bi-inbox" style="font-size:36px;color:#1a2235;display:block;margin-bottom:10px;"></i>
                            <span style="font-size:13px;color:#3a4d66;">No deposit history</span>
                        </div>
                    @endforelse
                    @if ($deposits->count() > 0)
                        <div style="padding:12px 16px;">
                            <a href="{{ route('member.deposit.history') }}" style="display:flex;align-items:center;justify-content:center;background:rgba(245,166,35,.06);border:1px solid rgba(245,166,35,.18);border-radius:10px;padding:10px;font-size:12px;font-weight:700;color:#f5a623;text-decoration:none;">View All Deposits</a>
                        </div>
                    @endif
                </div>

                {{-- Withdrawal List --}}
                <div id="withdrawal-list" class="xa-list">
                    @forelse($withdrawals as $withdrawal)
                        <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(26,34,53,.8);">
                            <div style="width:38px;height:38px;border-radius:11px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-arrow-up-circle" style="font-size:17px;color:#f5a623;"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px;">
                                    <div>
                                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;">Withdrawal</div>
                                        <div style="font-size:11px;color:#3a4d66;font-family:monospace;">{{ $withdrawal->reference }}</div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div style="font-size:13px;font-weight:700;color:#f5a623;font-family:monospace;">-{{ number_format($withdrawal->amount, 2) }}</div>
                                        <span class="xa-status {{ $withdrawal->status }}">{{ ucfirst($withdrawal->status) }}</span>
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:column;gap:3px;margin-top:4px;">
                                    <div style="display:flex;justify-content:space-between;">
                                        <span style="font-size:11px;color:#3a4d66;">Fee (5%)</span>
                                        <span style="font-size:11px;color:#3a4d66;font-family:monospace;">{{ number_format($withdrawal->withdrawal_fee, 2) }} USDT</span>
                                    </div>
                                    <span style="font-size:11px;color:#3a4d66;"><i class="bi bi-calendar3 me-1"></i>{{ $withdrawal->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:36px 20px;text-align:center;">
                            <i class="bi bi-inbox" style="font-size:36px;color:#1a2235;display:block;margin-bottom:10px;"></i>
                            <span style="font-size:13px;color:#3a4d66;">No withdrawal history</span>
                        </div>
                    @endforelse
                    @if ($withdrawals->count() > 0)
                        <div style="padding:12px 16px;">
                            <a href="{{ route('member.withdraw.history') }}" style="display:flex;align-items:center;justify-content:center;background:rgba(245,166,35,.06);border:1px solid rgba(245,166,35,.18);border-radius:10px;padding:10px;font-size:12px;font-weight:700;color:#f5a623;text-decoration:none;">View All Withdrawals</a>
                        </div>
                    @endif
                </div>

                {{-- Commission List --}}
                <div id="commission-list" class="xa-list">
                    @forelse($commissions as $commission)
                        <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(26,34,53,.8);">
                            <div style="width:38px;height:38px;border-radius:11px;background:rgba(153,69,255,.10);border:1px solid rgba(153,69,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-gift" style="font-size:17px;color:#9945ff;"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px;">
                                    <div>
                                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;">Commission</div>
                                        <div style="font-size:11px;color:#3a4d66;font-family:monospace;">{{ $commission->reference }}</div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div style="font-size:13px;font-weight:700;color:#9945ff;font-family:monospace;">+{{ number_format($commission->amount, 2) }}</div>
                                        <span class="xa-status {{ $commission->status }}">{{ ucfirst($commission->status) }}</span>
                                    </div>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
                                    <span style="font-size:11px;color:#3a4d66;"><i class="bi bi-calendar3 me-1"></i>{{ $commission->created_at->format('d M Y, H:i') }}</span>
                                    @if ($commission->source_user_id)
                                        <span style="font-size:11px;color:#f5a623;"><i class="bi bi-person me-1"></i>From referral</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:36px 20px;text-align:center;">
                            <i class="bi bi-inbox" style="font-size:36px;color:#1a2235;display:block;margin-bottom:10px;"></i>
                            <span style="font-size:13px;color:#3a4d66;">No commission history</span>
                        </div>
                    @endforelse
                    @if ($commissions->count() > 0)
                        <div style="padding:12px 16px;">
                            <a href="#" style="display:flex;align-items:center;justify-content:center;background:rgba(245,166,35,.06);border:1px solid rgba(245,166,35,.18);border-radius:10px;padding:10px;font-size:12px;font-weight:700;color:#f5a623;text-decoration:none;">View All Commissions</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── CARD 4: WALLET LIST ──────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Wallet List</span>
                    <span style="background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);border-radius:8px;padding:3px 10px;font-size:11px;font-weight:700;color:#f5a623;">{{ $wallets->count() }}/3</span>
                </div>

                {{-- Currency Info --}}
                <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid #1a2235;background:rgba(245,166,35,.03);">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="color:#f5a623;font-size:17px;font-weight:700;">₮</span>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#3a4d66;margin-bottom:2px;">Currency</div>
                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;">USDT (Tether)</div>
                    </div>
                </div>

                @forelse($wallets as $wallet)
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:14px 16px;border-bottom:1px solid rgba(26,34,53,.8);">
                        <div style="display:flex;align-items:flex-start;gap:12px;flex:1;min-width:0;">
                            <div style="width:38px;height:38px;border-radius:11px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
                                background:{{ $wallet->type === 'trc20' ? 'rgba(255,68,68,.10)' : 'rgba(243,186,47,.10)' }};
                                border:1px solid {{ $wallet->type === 'trc20' ? 'rgba(255,68,68,.20)' : 'rgba(243,186,47,.20)' }};">
                                <i class="bi bi-wallet-fill" style="font-size:17px;color:{{ $wallet->type === 'trc20' ? '#ff4444' : '#f3ba2f' }};"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="margin-bottom:4px;">
                                    <span style="display:inline-block;padding:2px 8px;border-radius:5px;font-size:9px;font-weight:700;letter-spacing:.5px;
                                        background:{{ $wallet->type === 'trc20' ? 'rgba(255,68,68,.12)' : 'rgba(243,186,47,.12)' }};
                                        color:{{ $wallet->type === 'trc20' ? '#ff4444' : '#f3ba2f' }};
                                        border:1px solid {{ $wallet->type === 'trc20' ? 'rgba(255,68,68,.25)' : 'rgba(243,186,47,.25)' }};">
                                        {{ strtoupper($wallet->type) }}
                                    </span>
                                </div>
                                <div style="font-size:12px;font-weight:700;color:#e2eaf8;margin-bottom:3px;font-family:monospace;word-break:break-all;">{{ $wallet->account_number }}</div>
                                <div style="font-size:11px;color:#f5a623;"><i class="bi bi-info-circle me-1"></i>{{ $wallet->getTypeLabel() }}</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:6px;flex-shrink:0;margin-left:10px;">
                            <button onclick="openEditModal({{ $wallet->id }}, '{{ $wallet->type }}', '{{ $wallet->account_number }}')"
                                style="width:32px;height:32px;border-radius:8px;background:rgba(100,160,255,.10);border:1px solid rgba(100,160,255,.20);display:flex;align-items:center;justify-content:center;color:#64a0ff;cursor:pointer;">
                                <i class="bi bi-pencil" style="font-size:12px;"></i>
                            </button>
                            <form action="{{ route('member.wallet.destroy', $wallet->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus wallet ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="width:32px;height:32px;border-radius:8px;background:rgba(240,79,90,.10);border:1px solid rgba(240,79,90,.20);display:flex;align-items:center;justify-content:center;color:#f04f5a;cursor:pointer;">
                                    <i class="bi bi-trash" style="font-size:12px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="padding:30px 20px;text-align:center;">
                        <span style="font-size:13px;color:#3a4d66;">Belum ada wallet</span>
                    </div>
                @endforelse

                <div style="padding:12px 16px;">
                    <button data-bs-toggle="modal" data-bs-target="#addWalletModal" @if($wallets->count() >= 3) disabled @endif
                        style="width:100%;background:rgba(245,166,35,.06);border:1px solid rgba(245,166,35,.20);border-radius:12px;padding:11px;font-size:13px;font-weight:700;color:#f5a623;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;opacity:{{ $wallets->count() >= 3 ? '.4' : '1' }};">
                        <i class="bi bi-plus-circle"></i> Tambah Wallet
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ── MODAL ADD WALLET ─────────────────────────────── --}}
    <div class="modal fade" id="addWalletModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;">
                <div class="modal-header" style="border-bottom:1px solid #1a2235;padding:16px 18px;">
                    <h5 class="modal-title" style="color:#e2eaf8;font-size:15px;font-weight:700;">Tambah Wallet</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('member.wallet.store') }}" method="POST">
                    @csrf
                    <div class="modal-body" style="padding:18px;">
                        {{-- Currency --}}
                        <div style="display:flex;align-items:center;gap:10px;background:rgba(245,166,35,.05);border:1px solid rgba(245,166,35,.12);border-radius:10px;padding:12px;margin-bottom:16px;">
                            <div style="width:36px;height:36px;border-radius:10px;background:rgba(245,166,35,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <span style="color:#f5a623;font-size:17px;font-weight:700;">₮</span>
                            </div>
                            <div>
                                <div style="font-size:10px;color:#3a4d66;margin-bottom:2px;">Currency</div>
                                <div style="font-size:13px;font-weight:700;color:#e2eaf8;">USDT (Tether)</div>
                            </div>
                        </div>

                        {{-- Network Type --}}
                        <div style="margin-bottom:16px;">
                            <label style="font-size:12px;font-weight:600;color:#7a8fad;display:block;margin-bottom:8px;">Network Type</label>
                            <div style="display:flex;flex-direction:column;gap:8px;">
                                @foreach([['trc20','TRC20','TRON Network','rgba(255,68,68,.12)','rgba(255,68,68,.22)','#ff4444'],['bep20','BEP20','Binance Smart Chain','rgba(243,186,47,.12)','rgba(243,186,47,.22)','#f3ba2f']] as [$val,$name,$desc,$bg,$border,$clr])
                                <label style="cursor:pointer;margin:0;">
                                    <input type="radio" name="type" value="{{ $val }}" {{ $val === 'trc20' ? 'checked' : '' }} style="display:none;" class="xa-network-radio">
                                    <div class="xa-network-card" style="display:flex;align-items:center;gap:12px;padding:12px;background:rgba(255,255,255,.03);border:2px solid #1a2235;border-radius:10px;transition:all .15s;">
                                        <div style="width:38px;height:38px;border-radius:10px;background:{{ $bg }};border:1px solid {{ $border }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="bi bi-circle-fill" style="font-size:18px;color:{{ $clr }};"></i>
                                        </div>
                                        <div style="flex:1;">
                                            <div style="font-size:13px;font-weight:700;color:#e2eaf8;">{{ $name }}</div>
                                            <div style="font-size:11px;color:#3a4d66;">{{ $desc }}</div>
                                        </div>
                                        <i class="bi bi-check-circle-fill xa-network-check" style="font-size:18px;color:#f5a623;opacity:{{ $val === 'trc20' ? '1' : '0' }};transition:opacity .15s;"></i>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('type')<small style="color:#f04f5a;">{{ $message }}</small>@enderror
                        </div>

                        {{-- Wallet Address --}}
                        <div>
                            <label style="font-size:12px;font-weight:600;color:#7a8fad;display:block;margin-bottom:8px;">Wallet Address</label>
                            <input type="text" name="account_number" placeholder="Masukkan wallet address" required value="{{ old('account_number') }}"
                                style="width:100%;background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:10px;padding:11px 14px;font-size:13px;color:#e2eaf8;outline:none;font-family:monospace;">
                            <div style="font-size:11px;color:#3a4d66;margin-top:5px;"><i class="bi bi-info-circle me-1"></i>Pastikan address sesuai dengan network yang dipilih</div>
                            @error('account_number')<small style="color:#f04f5a;">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #1a2235;padding:14px 18px;gap:8px;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="background:rgba(255,255,255,.04);border:1px solid #1a2235;color:#7a8fad;border-radius:10px;padding:9px 18px;font-size:13px;">Batal</button>
                        <button type="submit" style="background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);border:none;border-radius:10px;padding:9px 22px;font-size:13px;font-weight:700;color:#080b12;cursor:pointer;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── MODAL EDIT WALLET ────────────────────────────── --}}
    <div class="modal fade" id="editWalletModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;">
                <div class="modal-header" style="border-bottom:1px solid #1a2235;padding:16px 18px;">
                    <h5 class="modal-title" style="color:#e2eaf8;font-size:15px;font-weight:700;">Edit Wallet</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editWalletForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body" style="padding:18px;">
                        {{-- Currency --}}
                        <div style="display:flex;align-items:center;gap:10px;background:rgba(245,166,35,.05);border:1px solid rgba(245,166,35,.12);border-radius:10px;padding:12px;margin-bottom:16px;">
                            <div style="width:36px;height:36px;border-radius:10px;background:rgba(245,166,35,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <span style="color:#f5a623;font-size:17px;font-weight:700;">₮</span>
                            </div>
                            <div>
                                <div style="font-size:10px;color:#3a4d66;margin-bottom:2px;">Currency</div>
                                <div style="font-size:13px;font-weight:700;color:#e2eaf8;">USDT (Tether)</div>
                            </div>
                        </div>

                        {{-- Network Type --}}
                        <div style="margin-bottom:16px;">
                            <label style="font-size:12px;font-weight:600;color:#7a8fad;display:block;margin-bottom:8px;">Network Type</label>
                            <div style="display:flex;flex-direction:column;gap:8px;">
                                @foreach([['trc20','edit_type_trc20','TRC20','TRON Network','rgba(255,68,68,.12)','rgba(255,68,68,.22)','#ff4444'],['bep20','edit_type_bep20','BEP20','Binance Smart Chain','rgba(243,186,47,.12)','rgba(243,186,47,.22)','#f3ba2f']] as [$val,$id,$name,$desc,$bg,$border,$clr])
                                <label style="cursor:pointer;margin:0;">
                                    <input type="radio" name="type" value="{{ $val }}" id="{{ $id }}" style="display:none;" class="xa-network-radio">
                                    <div class="xa-network-card" style="display:flex;align-items:center;gap:12px;padding:12px;background:rgba(255,255,255,.03);border:2px solid #1a2235;border-radius:10px;transition:all .15s;">
                                        <div style="width:38px;height:38px;border-radius:10px;background:{{ $bg }};border:1px solid {{ $border }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="bi bi-circle-fill" style="font-size:18px;color:{{ $clr }};"></i>
                                        </div>
                                        <div style="flex:1;">
                                            <div style="font-size:13px;font-weight:700;color:#e2eaf8;">{{ $name }}</div>
                                            <div style="font-size:11px;color:#3a4d66;">{{ $desc }}</div>
                                        </div>
                                        <i class="bi bi-check-circle-fill xa-network-check" style="font-size:18px;color:#f5a623;opacity:0;transition:opacity .15s;"></i>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('type')<small style="color:#f04f5a;">{{ $message }}</small>@enderror
                        </div>

                        {{-- Wallet Address --}}
                        <div>
                            <label style="font-size:12px;font-weight:600;color:#7a8fad;display:block;margin-bottom:8px;">Wallet Address</label>
                            <input type="text" name="account_number" id="edit_account_number" placeholder="Masukkan wallet address" required
                                style="width:100%;background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:10px;padding:11px 14px;font-size:13px;color:#e2eaf8;outline:none;font-family:monospace;">
                            <div style="font-size:11px;color:#3a4d66;margin-top:5px;"><i class="bi bi-info-circle me-1"></i>Pastikan address sesuai dengan network yang dipilih</div>
                            @error('account_number')<small style="color:#f04f5a;">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #1a2235;padding:14px 18px;gap:8px;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="background:rgba(255,255,255,.04);border:1px solid #1a2235;color:#7a8fad;border-radius:10px;padding:9px 18px;font-size:13px;">Batal</button>
                        <button type="submit" style="background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);border:none;border-radius:10px;padding:9px 22px;font-size:13px;font-weight:700;color:#080b12;cursor:pointer;">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Tab active state */
        .xa-tab.active { color: #f5a623 !important; background: rgba(245,166,35,.06) !important; }
        .xa-tab:not(.active) .xa-tab-line { display: none; }

        /* Transaction list show/hide */
        .xa-list { display: none; }
        .xa-list.active { display: block; }

        /* Status badge */
        .xa-status {
            display: inline-block; padding: 2px 7px; border-radius: 5px;
            font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .3px;
        }
        .xa-status.pending  { background: rgba(245,166,35,.12); color: #f5a623; border: 1px solid rgba(245,166,35,.25); }
        .xa-status.approved { background: rgba(100,160,255,.12); color: #64a0ff; border: 1px solid rgba(100,160,255,.25); }
        .xa-status.completed{ background: rgba(0,212,138,.12); color: #00d48a; border: 1px solid rgba(0,212,138,.25); }
        .xa-status.rejected { background: rgba(240,79,90,.12); color: #f04f5a; border: 1px solid rgba(240,79,90,.25); }

        /* Network card selected */
        .xa-network-radio:checked ~ .xa-network-card {
            background: rgba(245,166,35,.08) !important;
            border-color: rgba(245,166,35,.40) !important;
        }
        .xa-network-radio:checked ~ .xa-network-card .xa-network-check { opacity: 1 !important; }
    </style>

    <script>
        function switchTransactionTab(type) {
            document.querySelectorAll('.xa-tab').forEach(t => {
                t.classList.remove('active');
                t.style.color = '#3a4d66';
                const line = t.querySelector('.xa-tab-line');
                if (line) line.style.display = 'none';
                const count = t.querySelector('span:last-child');
                if (count) { count.style.background = 'rgba(255,255,255,.06)'; count.style.color = '#3a4d66'; }
            });
            document.querySelectorAll('.xa-list').forEach(l => l.classList.remove('active'));

            const activeTab = event.target.closest('.xa-tab');
            activeTab.classList.add('active');
            activeTab.style.color = '#f5a623';
            const line = activeTab.querySelector('.xa-tab-line');
            if (line) line.style.display = 'block';
            const count = activeTab.querySelector('span:last-child');
            if (count) { count.style.background = '#f5a623'; count.style.color = '#080b12'; }
            document.getElementById(type + '-list').classList.add('active');
        }

        function openEditModal(id, type, accountNumber) {
            document.getElementById('editWalletForm').action = "{{ url('member/wallet') }}/" + id;
            document.getElementById('edit_account_number').value = accountNumber;
            document.getElementById('edit_type_trc20').checked = type === 'trc20';
            document.getElementById('edit_type_bep20').checked = type === 'bep20';
            // Update check icon visibility
            document.querySelectorAll('#editWalletModal .xa-network-radio').forEach(r => {
                const check = r.nextElementSibling.querySelector('.xa-network-check');
                if (check) check.style.opacity = r.checked ? '1' : '0';
            });
            new bootstrap.Modal(document.getElementById('editWalletModal')).show();
        }

        // Network radio visual update
        document.querySelectorAll('.xa-network-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const form = this.closest('form, .modal-body');
                form.querySelectorAll('.xa-network-radio').forEach(r => {
                    const check = r.nextElementSibling.querySelector('.xa-network-check');
                    if (check) check.style.opacity = r.checked ? '1' : '0';
                });
            });
        });

        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(a => { try { new bootstrap.Alert(a).close(); } catch(e){} });
        }, 5000);
    </script>
@endsection