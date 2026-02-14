@extends('member.layouts.app')
@section('content')
    <!-- Scrollable Content Area -->
    <div class="scrollable-content">
        <div class="content-section">
            <!-- Header with Back & History Buttons -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('member.profile.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                <a href="{{ route('member.withdraw.history') }}" class="btn-history">
                    <i class="bi bi-clock-history me-1"></i>History
                </a>
            </div>

            <h5 class="text-white mb-3">Withdraw</h5>

            <!-- Currency Info Card -->
            <div class="card-dark shadow-sm p-3 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div
                        style="width: 40px; height: 40px; background: rgba(245, 166, 35, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: var(--gold-color); font-size: 20px; font-weight: bold;">₮</span>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Currency</p>
                        <h6 class="text-white mb-0 fw-bold">USDT (Tether)</h6>
                    </div>
                </div>
            </div>

            <!-- Verification Alert (if not verified) -->
            @if (!auth()->user()->is_verified)
                <div class="alert alert-warning mb-3"
                    style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 8px; padding: 12px;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning"
                            style="font-size: 20px; margin-top: 2px;"></i>
                        <div>
                            <h6 class="text-warning mb-1" style="font-size: 14px; font-weight: 600;">Akun Belum
                                Terverifikasi</h6>
                            <p class="small text-white mb-0" style="font-size: 13px;">
                                Akun Anda belum terverifikasi. Untuk melakukan verifikasi akun kunjungi profile dan klik
                                verifikasi akun.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form id="withdraw-form" action="{{ route('member.withdraw.store') }}" method="POST">
                @csrf

                <!-- Current Balance Info -->
                <div class="card-dark shadow-sm p-3 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Available Balance</p>
                            <h5 class="text-gold mb-0 fw-bold">{{ number_format($userBalance, 2) }} USDT</h5>
                        </div>
                        <div class="balance-icon-wrapper">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Amount Card -->
                <div class="card-dark shadow-sm p-3 mb-3">
                    <h6 class="text-white mb-3">Withdrawal Amount</h6>
                    <div class="mb-3">
                        <label class="text-muted small mb-2 d-block">Amount (USDT)</label>
                        <div class="input-with-icon">
                            <span class="input-icon">₮</span>
                            <input type="number" name="amount" id="withdraw-amount" class="form-control-dark with-icon"
                                placeholder="Enter amount" value="{{ old('amount') }}" step="0.01" min="20"
                                {{ !auth()->user()->is_verified ? 'disabled' : 'required' }}>
                        </div>
                        <small class="text-muted d-block mt-1">Minimum withdrawal: 20 USDT | Fee: 5 USDT (< 100 USDT) or 5%
                                (≥ 100 USDT)</small>
                                @error('amount')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small mb-2 d-block">Or choose quick amount:</label>
                    </div>
                    <div class="amount-quick-select">
                        <button type="button" class="quick-amount-btn" onclick="setWithdrawAmount(20)"
                            {{ !auth()->user()->is_verified ? 'disabled' : '' }}>20 USDT</button>
                        <button type="button" class="quick-amount-btn" onclick="setWithdrawAmount(50)"
                            {{ !auth()->user()->is_verified ? 'disabled' : '' }}>50 USDT</button>
                        <button type="button" class="quick-amount-btn" onclick="setWithdrawAmount(100)"
                            {{ !auth()->user()->is_verified ? 'disabled' : '' }}>100 USDT</button>
                        <button type="button" class="quick-amount-btn" onclick="setWithdrawAmount(250)"
                            {{ !auth()->user()->is_verified ? 'disabled' : '' }}>250 USDT</button>
                    </div>
                </div>

                <!-- Select Wallet Account Card -->
                <div class="card-dark shadow-sm p-3 mb-3">
                    <h6 class="text-white mb-3">Select Wallet Account</h6>
                    <div>
                        <label class="text-muted small mb-2 d-block">Choose your wallet account</label>
                        <select name="wallet_id" id="wallet-account" class="form-control-dark-select"
                            {{ !auth()->user()->is_verified || $wallets->isEmpty() ? 'disabled' : 'required' }}>
                            <option value="">-- Select Wallet Account --</option>
                            @forelse($wallets as $wallet)
                                <option value="{{ $wallet->id }}"
                                    {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                    {{ $wallet->account_number }} - {{ $wallet->type }}
                                </option>
                            @empty
                                <option value="" disabled>No wallet account available</option>
                            @endforelse
                        </select>
                        @error('wallet_id')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror

                        @if ($wallets->isEmpty())
                            <div class="alert-info-box mt-2">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <span class="small">You need to add a wallet account first.
                                    href="{{ route('member.profile.index') }}" class="text-gold">Add Wallet</a></span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Fee Calculation -->
                <div class="card-dark shadow-sm p-3 mb-3" id="fee-card" style="display: none;">
                    <h6 class="text-white mb-3">Withdrawal Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Withdrawal Amount</span>
                        <span class="text-white fw-bold" id="display-amount">0.00 USDT</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Withdrawal Fee</span>
                        <span class="text-white fw-bold" id="display-fee">0.00 USDT</span>
                    </div>
                    <hr style="border-color: rgba(255,255,255,0.1);">
                    <div class="d-flex justify-content-between">
                        <span class="text-white fw-bold">You will receive</span>
                        <span class="text-gold fw-bold" id="display-total">0.00 USDT</span>
                    </div>
                </div>

                

                <!-- Submit Button -->
                <button type="button" class="btn btn-gold w-100" onclick="submitWithdraw()"
                    {{ !auth()->user()->is_verified || $wallets->isEmpty() ? 'disabled' : '' }}>
                    Submit Withdrawal
                </button>
            </form>

        </div>
    </div>

    <style>
        /* History Button Style */
        .btn-history {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background: rgba(245, 166, 35, 0.1);
            border: 1px solid rgba(245, 166, 35, 0.3);
            border-radius: 8px;
            color: var(--gold-color);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-history:hover {
            background: rgba(245, 166, 35, 0.2);
            border-color: var(--gold-color);
            color: var(--gold-color);
            text-decoration: none;
        }

        .btn-history i {
            font-size: 14px;
        }

        /* Improved Dropdown Styling */
        .form-control-dark-select {
            background: rgba(245, 166, 35, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23f5a623' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        .form-control-dark-select:focus {
            outline: none;
            border-color: var(--gold-color);
            background-color: rgba(245, 166, 35, 0.1);
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.1);
        }

        .form-control-dark-select option {
            background-color: var(--card-dark);
            color: #ffffff;
            padding: 12px;
            font-size: 14px;
        }

        .form-control-dark-select option:hover {
            background-color: rgba(245, 166, 35, 0.2);
        }

        .form-control-dark-select option:checked {
            background-color: var(--gold-color);
            color: #000;
        }

        .form-control-dark-select option[value=""] {
            color: var(--text-muted);
        }

        .form-control-dark-select:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Better dropdown for mobile */
        @media (max-width: 480px) {
            .form-control-dark-select {
                font-size: 16px;
                /* Prevents zoom on iOS */
            }
        }

        /* Disabled state for quick amount buttons */
        .quick-amount-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

    <script>
        const userBalance = {{ $userBalance }};
        const isVerified = {{ auth()->user()->is_verified ? 'true' : 'false' }};

        // Show alert messages
        @if (session('success'))
            alert('{{ session('success') }}');
        @endif

        @if (session('error'))
            alert('{{ session('error') }}');
        @endif

        @if ($errors->any())
            alert('{{ $errors->first() }}');
        @endif

        function setWithdrawAmount(amount) {
            if (!isVerified) {
                alert('Akun Anda belum terverifikasi. Silakan hubungi admin untuk verifikasi akun.');
                return;
            }
            document.getElementById('withdraw-amount').value = amount;
            calculateFee();
        }

        // Calculate fee when amount changes
        document.getElementById('withdraw-amount').addEventListener('input', function() {
            calculateFee();
        });

        function calculateFee() {
            const amount = parseFloat(document.getElementById('withdraw-amount').value) || 0;

            if (amount > 0) {
                // FEE LOGIC: 5 USDT for < 100, 5% for >= 100
                let fee;
                if (amount < 100) {
                    fee = 5; // Fixed 5 USDT for amounts below 100
                } else {
                    fee = amount * 0.05; // 5% for amounts 100 and above
                }

                const total = amount - fee;

                document.getElementById('display-amount').textContent = amount.toFixed(2) + ' USDT';
                document.getElementById('display-fee').textContent = fee.toFixed(2) + ' USDT';
                document.getElementById('display-total').textContent = total.toFixed(2) + ' USDT';
                document.getElementById('fee-card').style.display = 'block';
            } else {
                document.getElementById('fee-card').style.display = 'none';
            }
        }

        function submitWithdraw() {
            if (!isVerified) {
                alert('Akun Anda belum terverifikasi. Withdrawal tidak dapat diproses. Silakan hubungi admin.');
                return;
            }

            const amount = parseFloat(document.getElementById('withdraw-amount').value);
            const walletSelect = document.getElementById('wallet-account');

            if (!amount || amount <= 0) {
                alert('Please enter a valid amount');
                return;
            }

            if (amount < 20) {
                alert('Minimum withdrawal amount is 20 USDT');
                return;
            }

            if (amount > userBalance) {
                alert('Insufficient balance. Your available balance is ' + userBalance.toFixed(2) + ' USDT');
                return;
            }

            if (!walletSelect.value) {
                alert('Please select a wallet account');
                return;
            }

            // FEE CALCULATION: 5 USDT for < 100, 5% for >= 100
            let fee;
            if (amount < 100) {
                fee = 5; // Fixed 5 USDT
            } else {
                fee = amount * 0.05; // 5%
            }

            const total = amount - fee;

            if (total <= 0) {
                alert('Amount too small. After fee deduction, you will receive 0 USDT or less.');
                return;
            }

            // Confirm before submit
            if (confirm('Confirm withdrawal?\n\nAmount: ' + amount.toFixed(2) + ' USDT\nFee: ' + fee.toFixed(2) +
                    ' USDT\nYou will receive: ' + total.toFixed(2) + ' USDT')) {
                document.getElementById('withdraw-form').submit();
            }
        }

        // Enhanced dropdown behavior
        document.getElementById('wallet-account').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            // Visual feedback when wallet is selected
            if (this.value) {
                this.style.borderColor = 'var(--gold-color)';
                this.style.backgroundColor = 'rgba(245, 166, 35, 0.1)';
            } else {
                this.style.borderColor = 'var(--border-color)';
                this.style.backgroundColor = 'rgba(245, 166, 35, 0.05)';
            }
        });

        // Auto-select first wallet if only one available and no previous selection
        window.addEventListener('DOMContentLoaded', function() {
            if (!isVerified) return; // Skip auto-select if not verified

            const walletSelect = document.getElementById('wallet-account');
            const options = walletSelect.querySelectorAll('option[value]:not([value=""])');

            // If only one wallet available and nothing selected, auto-select it
            if (options.length === 1 && !walletSelect.value) {
                walletSelect.value = options[0].value;
                walletSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
