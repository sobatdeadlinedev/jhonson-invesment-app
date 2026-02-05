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
                <a href="{{ route('member.deposit.history') }}" class="btn-history">
                    <i class="bi bi-clock-history me-1"></i>History
                </a>
            </div>

            <h5 class="text-white mb-3">Deposit</h5>

            <!-- Step Indicator -->
            <div class="step-indicator mb-4">
                <div class="step-item active" id="step-1-indicator">
                    <div class="step-circle">1</div>
                    <div class="step-label">Amount</div>
                </div>
                <div class="step-line"></div>
                <div class="step-item" id="step-2-indicator">
                    <div class="step-circle">2</div>
                    <div class="step-label">Payment</div>
                </div>
            </div>

            <!-- Step 1: Amount -->
            <div id="step-1" class="step-content active">
                <!-- Current Balance Info -->
                <div class="card-dark shadow-sm p-3 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Current Balance</p>
                            <h5 class="text-gold mb-0 fw-bold">{{ number_format($userBalance, 2) }} USDT</h5>
                        </div>
                        <div class="balance-icon-wrapper">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>

                <!-- Amount Input Card -->
                <div class="card-dark shadow-sm p-3 mb-3">
                    <h6 class="text-white mb-3">Deposit Amount</h6>
                    <div class="mb-3">
                        <label class="text-muted small mb-2 d-block">Enter Amount (USDT)</label>
                        <div class="input-with-icon">
                            <span class="input-icon">₮</span>
                            <input type="number" id="deposit-amount" class="form-control-dark with-icon"
                                placeholder="Enter amount manually" value="" step="0.01" min="10">
                        </div>
                    </div>
                    
                </div>

                <!-- Continue Button -->
                <button class="btn btn-gold w-100" type="button" onclick="goToStep2()">
                    Continue <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>

            <!-- Step 2: Payment Method -->
            <div id="step-2" class="step-content">
                <form id="deposit-form" action="{{ route('member.deposit.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="amount" id="form-amount">
                    <input type="hidden" name="payment_method" id="form-payment-method" value="ewallet">

                    <!-- Amount Summary -->
                    <div class="card-dark shadow-sm p-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="text-muted mb-0">Deposit Amount</p>
                            <h5 class="text-gold mb-0 fw-bold" id="summary-amount">100.00 USDT</h5>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="card-dark shadow-sm p-3 mb-3">
                        <h6 class="text-white mb-3">Choose Payment Method</h6>

                        <!-- E-Wallet Option -->
                        <div class="payment-method-option" onclick="selectMethod('ewallet')">
                            <input type="radio" name="payment_method_display" id="method-ewallet" class="payment-radio"
                                checked>
                            <label for="method-ewallet" class="payment-label">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="payment-icon ewallet">
                                        <i class="bi bi-wallet2"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold mb-1" style="font-size: 14px;">E-Wallet</div>
                                        <small class="text-muted">Transfer USDT via E-Wallet</small>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- QR Code Option -->
                        <div class="payment-method-option" onclick="selectMethod('qrcode')">
                            <input type="radio" name="payment_method_display" id="method-qrcode" class="payment-radio">
                            <label for="method-qrcode" class="payment-label">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="payment-icon qrcode">
                                        <i class="bi bi-qr-code"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold mb-1" style="font-size: 14px;">QR Code</div>
                                        <small class="text-muted">Scan QR to pay with USDT</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Details: E-Wallet -->
                    <div id="payment-details-ewallet" class="payment-details active">
                        <div class="card-dark shadow-sm p-3 mb-3">
                            <h6 class="text-white mb-3">USDT</h6>
                            
                            <!-- Jaringan -->
                            <div class="payment-info-item">
                                <div class="payment-info-row">
                                    <span class="text-muted small payment-label">Jaringan</span>
                                    <div class="payment-value-with-copy">
                                        <span class="text-white fw-bold payment-value-text">{{ $walletNumber }}</span>
                                        <button type="button" class="btn-copy-mini"
                                            onclick="copyText('{{ $walletNumber }}')" title="Copy">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Alamat Setoran - Fixed Responsive Layout -->
                            <div class="payment-info-item">
                                <div class="payment-info-row">
                                    <span class="text-muted small payment-label">Alamat Setoran</span>
                                    <div class="payment-value-with-copy">
                                        <span class="text-white fw-bold payment-value-text wallet-address">{{ $walletName }}</span>
                                        <button type="button" class="btn-copy-mini"
                                            onclick="copyText('{{ $walletName }}')" title="Copy">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert-info-box mt-3">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <span class="small">Transfer USDT sesuai nominal yang tertera menggunakan network TRC20
                                    dan BEP 20. Lalu upload bukti transfer</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details: QR Code -->
                    <div id="payment-details-qrcode" class="payment-details">
                        <div class="card-dark shadow-sm p-3 mb-3">
                            <h6 class="text-white mb-3 text-center">Scan QR Code</h6>
                            <div class="qr-code-container">
                                <img src="{{ $qrCode }}" alt="QR Code" class="qr-code-image">
                            </div>
                            <div class="alert-info-box mt-3">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <span class="small">Scan QR code dengan aplikasi crypto wallet Anda (TRC20 Network) dan
                                    upload
                                    bukti transfer</span>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Proof -->
                    <div class="card-dark shadow-sm p-3 mb-3">
                        <h6 class="text-white mb-3">Upload Proof of Transfer</h6>
                        <div class="upload-area" onclick="document.getElementById('file-upload').click()">
                            <input type="file" name="payment_proof" id="file-upload" accept="image/*"
                                style="display: none;" onchange="handleFileUpload(event)" required>
                            <div id="upload-placeholder">
                                <i class="bi bi-cloud-upload upload-icon"></i>
                                <p class="text-white mb-1">Click to upload</p>
                                <small class="text-muted">PNG, JPG up to 5MB</small>
                            </div>
                            <div id="upload-preview" style="display: none;">
                                <img id="preview-image" src="" alt="Preview" class="preview-image">
                                <p class="text-white mb-0 mt-2" id="file-name"></p>
                            </div>
                        </div>
                        @error('payment_proof')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-gold w-100" onclick="goToStep1()">
                                <i class="bi bi-arrow-left me-2"></i>Back
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-gold w-100" onclick="submitDeposit()">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>

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

        /* Payment Info Responsive Layout */
        .payment-info-item {
            padding: 12px 0;
        }

        .payment-info-item:last-child {
            padding-bottom: 0;
        }

        .payment-info-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .payment-label {
            flex-shrink: 0;
            min-width: 100px;
        }

        .payment-value-with-copy {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            justify-content: flex-end;
        }

        .payment-value-text {
            font-size: 14px;
            line-height: 1.5;
            word-break: break-all;
            text-align: right;
        }

        .wallet-address {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .payment-info-row {
                flex-direction: column;
                gap: 8px;
                align-items: flex-start;
            }
            
            .payment-label {
                min-width: auto;
            }
            
            .payment-value-with-copy {
                width: 100%;
                justify-content: space-between;
            }

            .payment-value-text {
                text-align: left;
                font-size: 12px;
                flex: 1;
                word-break: break-all;
            }

            .wallet-address {
                font-size: 11px;
            }
        }

        /* Button copy styling */
        .btn-copy-mini {
            background: rgba(245, 166, 35, 0.1);
            border: 1px solid rgba(245, 166, 35, 0.3);
            color: var(--gold-color);
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 12px;
        }

        .btn-copy-mini:hover {
            background: rgba(245, 166, 35, 0.2);
            border-color: var(--gold-color);
        }

        /* Alert info box */
        .alert-info-box {
            background: rgba(52, 152, 219, 0.1);
            border: 1px solid rgba(52, 152, 219, 0.3);
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: flex-start;
            color: #3498db;
        }

        .alert-info-box i {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-info-box .small {
            line-height: 1.5;
        }
    </style>

    <script>
        let selectedFile = null;

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

        function setAmount(amount) {
            document.getElementById('deposit-amount').value = amount;
        }

        function goToStep2() {
            const amount = document.getElementById('deposit-amount').value;
            if (!amount || amount <= 0) {
                alert('Please enter a valid amount');
                return;
            }
            if (amount < 10) {
                alert('Minimum deposit amount is 10 USDT');
                return;
            }

            // Update summary
            document.getElementById('summary-amount').textContent = parseFloat(amount).toFixed(2) + ' USDT';
            document.getElementById('form-amount').value = amount;

            // Switch steps
            document.getElementById('step-1').classList.remove('active');
            document.getElementById('step-2').classList.add('active');

            // Update indicators
            document.getElementById('step-1-indicator').classList.remove('active');
            document.getElementById('step-1-indicator').classList.add('completed');
            document.getElementById('step-2-indicator').classList.add('active');

            // Scroll to top
            document.querySelector('.scrollable-content').scrollTop = 0;
        }

        function goToStep1() {
            document.getElementById('step-2').classList.remove('active');
            document.getElementById('step-1').classList.add('active');

            document.getElementById('step-2-indicator').classList.remove('active');
            document.getElementById('step-1-indicator').classList.add('active');
            document.getElementById('step-1-indicator').classList.remove('completed');

            document.querySelector('.scrollable-content').scrollTop = 0;
        }

        function selectMethod(method) {
            document.getElementById('form-payment-method').value = method;

            if (method === 'ewallet') {
                document.getElementById('method-ewallet').checked = true;
                document.getElementById('payment-details-ewallet').classList.add('active');
                document.getElementById('payment-details-qrcode').classList.remove('active');
            } else {
                document.getElementById('method-qrcode').checked = true;
                document.getElementById('payment-details-qrcode').classList.add('active');
                document.getElementById('payment-details-ewallet').classList.remove('active');
            }
        }

        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied: ' + text);
            });
        }

        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must not exceed 5MB');
                    event.target.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Only JPG, JPEG, and PNG files are allowed');
                    event.target.value = '';
                    return;
                }

                selectedFile = file;
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('file-name').textContent = file.name;
                    document.getElementById('upload-placeholder').style.display = 'none';
                    document.getElementById('upload-preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        function submitDeposit() {
            const amount = document.getElementById('deposit-amount').value;
            const fileInput = document.getElementById('file-upload');

            if (!fileInput.files || !fileInput.files[0]) {
                alert('Please upload proof of transfer');
                return;
            }

            // Confirm before submit
            if (confirm('Are you sure you want to submit this deposit request?')) {
                document.getElementById('deposit-form').submit();
            }
        }
    </script>
@endsection