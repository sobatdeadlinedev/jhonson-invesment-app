@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── HEADER ───────────────────────────────────────── --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                <a href="{{ route('member.profile.index') }}" style="display:inline-flex;align-items:center;gap:7px;background:#0d1120;border:1px solid #1a2235;border-radius:10px;padding:8px 14px;text-decoration:none;color:#7a8fad;font-size:13px;">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('member.withdraw.history') }}" style="display:inline-flex;align-items:center;gap:6px;background:rgba(245,166,35,.08);border:1px solid rgba(245,166,35,.22);border-radius:10px;padding:8px 14px;text-decoration:none;color:#f5a623;font-size:13px;font-weight:600;">
                    <i class="bi bi-clock-history"></i> History
                </a>
            </div>

            <div style="font-size:18px;font-weight:700;color:#e2eaf8;margin-bottom:16px;">Withdraw</div>

            {{-- ── CURRENCY INFO ─────────────────────────────────── --}}
            <div style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:14px 16px;display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                <div style="width:40px;height:40px;border-radius:12px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="color:#f5a623;font-size:20px;font-weight:700;">₮</span>
                </div>
                <div>
                    <div style="font-size:10px;color:#3a4d66;margin-bottom:2px;">Currency</div>
                    <div style="font-size:14px;font-weight:700;color:#e2eaf8;">USDT (Tether)</div>
                </div>
            </div>

            {{-- ── VERIFICATION ALERT ────────────────────────────── --}}
            @if (!auth()->user()->is_verified)
                <div style="background:rgba(245,166,35,.07);border:1px solid rgba(245,166,35,.25);border-left:3px solid #f5a623;border-radius:12px;padding:14px 16px;display:flex;align-items:flex-start;gap:10px;margin-bottom:12px;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f5a623;font-size:17px;flex-shrink:0;margin-top:1px;"></i>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#f5a623;margin-bottom:4px;">Akun Belum Terverifikasi</div>
                        <div style="font-size:12px;color:#e2eaf8;line-height:1.5;">Akun Anda belum terverifikasi. Untuk melakukan verifikasi akun kunjungi profile dan klik verifikasi akun.</div>
                    </div>
                </div>
            @endif

            <form id="withdraw-form" action="{{ route('member.withdraw.store') }}" method="POST">
                @csrf

                {{-- ── BALANCE ──────────────────────────────────── --}}
                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:16px;padding:16px;display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:90px;height:90px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.10) 0%,transparent 65%);pointer-events:none;"></div>
                    <div>
                        <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:5px;">Available Balance</div>
                        <div style="font-family:monospace;font-size:22px;font-weight:700;color:#f5a623;letter-spacing:-.5px;">{{ number_format($userBalance, 2) }} <span style="font-size:13px;color:#3a4d66;">USDT</span></div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:12px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-wallet2" style="font-size:18px;color:#f5a623;"></i>
                    </div>
                </div>

                {{-- ── AMOUNT INPUT ─────────────────────────────── --}}
                <div style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:16px;margin-bottom:12px;">
                    <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:12px;">Withdrawal Amount</div>
                    <div style="font-size:11px;color:#3a4d66;margin-bottom:8px;">Amount (USDT)</div>
                    <div style="display:flex;align-items:center;background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:10px;overflow:hidden;margin-bottom:8px;">
                        <div style="padding:12px 14px;background:rgba(245,166,35,.08);border-right:1px solid #1a2235;font-size:16px;font-weight:700;color:#f5a623;flex-shrink:0;">₮</div>
                        <input type="number" name="amount" id="withdraw-amount" placeholder="Enter amount" step="0.01" min="20"
                            value="{{ old('amount') }}"
                            {{ !auth()->user()->is_verified ? 'disabled' : 'required' }}
                            oninput="calculateFee()"
                            style="flex:1;background:transparent;border:none;outline:none;padding:12px 14px;font-size:14px;color:#e2eaf8;font-family:monospace;">
                    </div>
                    <div style="font-size:11px;color:#3a4d66;margin-bottom:12px;">Minimum withdrawal: 20 USDT &nbsp;|&nbsp; Fee: 5 USDT (&lt;100 USDT) or 5% (≥100 USDT)</div>
                    @error('amount')
                        <div style="font-size:12px;color:#f04f5a;margin-bottom:8px;">{{ $message }}</div>
                    @enderror

                    {{-- Quick amounts --}}
                    <div style="font-size:11px;color:#3a4d66;margin-bottom:8px;">Or choose quick amount:</div>
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px;">
                        @foreach([20,50,100,250] as $qa)
                        <button type="button" onclick="setWithdrawAmount({{ $qa }})"
                            {{ !auth()->user()->is_verified ? 'disabled' : '' }}
                            style="background:rgba(245,166,35,.08);border:1px solid rgba(245,166,35,.18);border-radius:8px;padding:8px 4px;font-size:12px;font-weight:600;color:#f5a623;cursor:pointer;transition:all .15s;{{ !auth()->user()->is_verified ? 'opacity:.4;cursor:not-allowed;' : '' }}"
                            onmouseover="if(!this.disabled)this.style.background='rgba(245,166,35,.16)'" onmouseout="this.style.background='rgba(245,166,35,.08)'">
                            {{ $qa }} USDT
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- ── SELECT WALLET ────────────────────────────── --}}
                <div style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:16px;margin-bottom:12px;">
                    <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:12px;">Select Wallet Account</div>
                    <div style="font-size:11px;color:#3a4d66;margin-bottom:8px;">Choose your wallet account</div>
                    <select name="wallet_id" id="wallet-account"
                        {{ !auth()->user()->is_verified || $wallets->isEmpty() ? 'disabled' : 'required' }}
                        style="width:100%;background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:10px;padding:12px 40px 12px 14px;font-size:13px;color:#e2eaf8;outline:none;appearance:none;
                        background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23f5a623' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E\");
                        background-repeat:no-repeat;background-position:right 12px center;{{ !auth()->user()->is_verified || $wallets->isEmpty() ? 'opacity:.4;cursor:not-allowed;' : '' }}">
                        <option value="" style="background:#0d1120;color:#3a4d66;">-- Select Wallet Account --</option>
                        @forelse($wallets as $wallet)
                            <option value="{{ $wallet->id }}" style="background:#0d1120;color:#e2eaf8;"
                                {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->account_number }} - {{ $wallet->type }}
                            </option>
                        @empty
                            <option value="" disabled style="background:#0d1120;color:#3a4d66;">No wallet account available</option>
                        @endforelse
                    </select>
                    @error('wallet_id')
                        <div style="font-size:12px;color:#f04f5a;margin-top:6px;">{{ $message }}</div>
                    @enderror

                    @if ($wallets->isEmpty())
                        <div style="background:rgba(100,160,255,.07);border:1px solid rgba(100,160,255,.18);border-radius:10px;padding:10px 12px;display:flex;align-items:flex-start;gap:8px;margin-top:10px;">
                            <i class="bi bi-info-circle-fill" style="color:#64a0ff;font-size:13px;flex-shrink:0;margin-top:1px;"></i>
                            <span style="font-size:12px;color:#64a0ff;">You need to add a wallet account first.
                                <a href="{{ route('member.profile.index') }}" style="color:#f5a623;font-weight:600;">Add Wallet</a>
                            </span>
                        </div>
                    @endif
                </div>

                {{-- ── FEE SUMMARY ──────────────────────────────── --}}
                <div id="fee-card" style="display:none;background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:16px;margin-bottom:14px;">
                    <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:12px;">Withdrawal Summary</div>
                    <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:10px;overflow:hidden;">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid #1a2235;">
                            <span style="font-size:12px;color:#3a4d66;">Withdrawal Amount</span>
                            <span style="font-size:13px;font-weight:700;color:#e2eaf8;font-family:monospace;" id="display-amount">0.00 USDT</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid #1a2235;">
                            <span style="font-size:12px;color:#3a4d66;">Withdrawal Fee</span>
                            <span style="font-size:13px;font-weight:700;color:#f04f5a;font-family:monospace;" id="display-fee">0.00 USDT</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:rgba(0,212,138,.04);">
                            <span style="font-size:13px;font-weight:700;color:#e2eaf8;">You will receive</span>
                            <span style="font-size:15px;font-weight:700;color:#00d48a;font-family:monospace;" id="display-total">0.00 USDT</span>
                        </div>
                    </div>
                </div>

                {{-- ── SUBMIT BUTTON ────────────────────────────── --}}
                <button type="button" onclick="submitWithdraw()"
                    {{ !auth()->user()->is_verified || $wallets->isEmpty() ? 'disabled' : '' }}
                    style="width:100%;background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);border:none;border-radius:14px;padding:14px;font-size:14px;font-weight:700;color:#080b12;cursor:pointer;box-shadow:0 4px 16px rgba(245,166,35,.28);{{ !auth()->user()->is_verified || $wallets->isEmpty() ? 'opacity:.4;cursor:not-allowed;' : '' }}">
                    Submit Withdrawal
                </button>

            </form>
        </div>
    </div>

    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; }
        select option { background: #0d1120; color: #e2eaf8; }
    </style>

    <script>
        const userBalance = {{ $userBalance }};
        const isVerified  = {{ auth()->user()->is_verified ? 'true' : 'false' }};

        @if (session('success')) alert('{{ session('success') }}'); @endif
        @if (session('error'))   alert('{{ session('error') }}');   @endif
        @if ($errors->any())     alert('{{ $errors->first() }}');   @endif

        function setWithdrawAmount(amount) {
            if (!isVerified) { alert('Akun Anda belum terverifikasi. Silakan hubungi admin untuk verifikasi akun.'); return; }
            document.getElementById('withdraw-amount').value = amount;
            calculateFee();
        }

        function calculateFee() {
            const amount = parseFloat(document.getElementById('withdraw-amount').value) || 0;
            if (amount > 0) {
                const fee   = amount < 100 ? 5 : amount * 0.05;
                const total = amount - fee;
                document.getElementById('display-amount').textContent = amount.toFixed(2) + ' USDT';
                document.getElementById('display-fee').textContent    = fee.toFixed(2) + ' USDT';
                document.getElementById('display-total').textContent  = total.toFixed(2) + ' USDT';
                document.getElementById('fee-card').style.display = 'block';
            } else {
                document.getElementById('fee-card').style.display = 'none';
            }
        }

        function submitWithdraw() {
            if (!isVerified) { alert('Akun Anda belum terverifikasi. Withdrawal tidak dapat diproses. Silakan hubungi admin.'); return; }
            const amount       = parseFloat(document.getElementById('withdraw-amount').value);
            const walletSelect = document.getElementById('wallet-account');
            if (!amount || amount <= 0)   { alert('Please enter a valid amount'); return; }
            if (amount < 20)              { alert('Minimum withdrawal amount is 20 USDT'); return; }
            if (amount > userBalance)     { alert('Insufficient balance. Your available balance is ' + userBalance.toFixed(2) + ' USDT'); return; }
            if (!walletSelect.value)      { alert('Please select a wallet account'); return; }
            const fee   = amount < 100 ? 5 : amount * 0.05;
            const total = amount - fee;
            if (total <= 0) { alert('Amount too small. After fee deduction, you will receive 0 USDT or less.'); return; }
            if (confirm('Confirm withdrawal?\n\nAmount: ' + amount.toFixed(2) + ' USDT\nFee: ' + fee.toFixed(2) + ' USDT\nYou will receive: ' + total.toFixed(2) + ' USDT')) {
                document.getElementById('withdraw-form').submit();
            }
        }

        // Wallet select visual feedback
        document.getElementById('wallet-account').addEventListener('change', function() {
            this.style.borderColor = this.value ? 'rgba(245,166,35,.40)' : '#1a2235';
        });

        // Auto-select if only one wallet
        window.addEventListener('DOMContentLoaded', function() {
            if (!isVerified) return;
            const sel  = document.getElementById('wallet-account');
            const opts = sel.querySelectorAll('option[value]:not([value=""])');
            if (opts.length === 1 && !sel.value) {
                sel.value = opts[0].value;
                sel.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection