@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── TOP NAV ──────────────────────────────────────── --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <a href="{{ route('member.profile.index') }}" style="display:inline-flex;align-items:center;gap:7px;background:#0d1120;border:1px solid #1a2235;border-radius:10px;padding:8px 14px;text-decoration:none;color:#7a8fad;font-size:13px;" onmouseover="this.style.color='#e2eaf8'" onmouseout="this.style.color='#7a8fad'">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('member.deposit.history') }}" style="display:inline-flex;align-items:center;gap:6px;background:rgba(245,166,35,.08);border:1px solid rgba(245,166,35,.22);border-radius:10px;padding:8px 14px;text-decoration:none;color:#f5a623;font-size:13px;font-weight:600;">
                    <i class="bi bi-clock-history"></i> History
                </a>
            </div>

            <div style="font-size:18px;font-weight:700;color:#e2eaf8;margin-bottom:16px;">Deposit</div>

            {{-- ── STEP INDICATOR ───────────────────────────────── --}}
            <div style="display:flex;align-items:center;margin-bottom:20px;">
                <div style="display:flex;flex-direction:column;align-items:center;gap:4px;" id="step-1-indicator">
                    <div class="xd-step-circle active" style="width:32px;height:32px;border-radius:50%;background:#f5a623;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#080b12;">1</div>
                    <div style="font-size:10px;color:#f5a623;font-weight:600;">Amount</div>
                </div>
                <div style="flex:1;height:2px;background:#1a2235;margin:0 8px 14px;position:relative;">
                    <div id="step-line" style="position:absolute;left:0;top:0;height:100%;width:0%;background:#f5a623;transition:width .3s;"></div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:center;gap:4px;" id="step-2-indicator">
                    <div class="xd-step-circle" style="width:32px;height:32px;border-radius:50%;background:#1a2235;border:2px solid #1a2235;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#3a4d66;">2</div>
                    <div style="font-size:10px;color:#3a4d66;font-weight:600;">Payment</div>
                </div>
            </div>

            {{-- ══ STEP 1 ═══════════════════════════════════════ --}}
            <div id="step-1" class="xd-step active">

                {{-- Current Balance --}}
                <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:16px;padding:16px;display:flex;align-items:center;justify-content:space-between;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:90px;height:90px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.10) 0%,transparent 65%);pointer-events:none;"></div>
                    <div>
                        <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:5px;">Current Balance</div>
                        <div style="font-family:monospace;font-size:22px;font-weight:700;color:#f5a623;letter-spacing:-.5px;">{{ number_format($userBalance, 2) }} <span style="font-size:13px;color:#3a4d66;">USDT</span></div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:12px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-wallet2" style="font-size:18px;color:#f5a623;"></i>
                    </div>
                </div>

                {{-- Amount Input --}}
                <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:16px;">
                    <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:14px;">Deposit Amount</div>
                    <div style="font-size:11px;color:#3a4d66;margin-bottom:8px;">Enter Amount (USDT)</div>
                    <div style="display:flex;align-items:center;background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:10px;overflow:hidden;">
                        <div style="padding:12px 14px;background:rgba(245,166,35,.08);border-right:1px solid #1a2235;font-size:16px;font-weight:700;color:#f5a623;flex-shrink:0;">₮</div>
                        <input type="number" id="deposit-amount" placeholder="Enter amount manually" step="0.01" min="10"
                            style="flex:1;background:transparent;border:none;outline:none;padding:12px 14px;font-size:14px;color:#e2eaf8;font-family:monospace;">
                    </div>
                </div>

                <button type="button" onclick="goToStep2()"
                    style="width:100%;background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);border:none;border-radius:14px;padding:14px;font-size:14px;font-weight:700;color:#080b12;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 16px rgba(245,166,35,.28);">
                    Continue <i class="bi bi-arrow-right"></i>
                </button>
            </div>

            {{-- ══ STEP 2 ═══════════════════════════════════════ --}}
            <div id="step-2" class="xd-step">
                <form id="deposit-form" action="{{ route('member.deposit.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="amount" id="form-amount">
                    <input type="hidden" name="payment_method" id="form-payment-method" value="ewallet">

                    {{-- Amount Summary --}}
                    <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:16px;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-size:13px;color:#7a8fad;">Deposit Amount</span>
                        <span style="font-family:monospace;font-size:18px;font-weight:700;color:#f5a623;" id="summary-amount">100.00 USDT</span>
                    </div>

                    {{-- Payment Methods --}}
                    <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:16px;">
                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:12px;">Choose Payment Method</div>
                        <div style="display:flex;flex-direction:column;gap:8px;">

                            <label style="cursor:pointer;margin:0;">
                                <input type="radio" name="payment_method_display" id="method-ewallet" class="xd-pm-radio" checked style="display:none;">
                                <div class="xd-pm-card" onclick="selectMethod('ewallet')" style="display:flex;align-items:center;gap:12px;padding:12px;background:rgba(245,166,35,.06);border:2px solid rgba(245,166,35,.35);border-radius:10px;">
                                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,166,35,.12);border:1px solid rgba(245,166,35,.22);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-wallet2" style="font-size:17px;color:#f5a623;"></i>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;">E-Wallet</div>
                                        <div style="font-size:11px;color:#3a4d66;">Transfer USDT via E-Wallet</div>
                                    </div>
                                    <i class="bi bi-check-circle-fill xd-pm-check" style="font-size:17px;color:#f5a623;"></i>
                                </div>
                            </label>

                            <label style="cursor:pointer;margin:0;">
                                <input type="radio" name="payment_method_display" id="method-qrcode" class="xd-pm-radio" style="display:none;">
                                <div class="xd-pm-card" onclick="selectMethod('qrcode')" style="display:flex;align-items:center;gap:12px;padding:12px;background:rgba(255,255,255,.03);border:2px solid #1a2235;border-radius:10px;">
                                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(100,160,255,.10);border:1px solid rgba(100,160,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-qr-code" style="font-size:17px;color:#64a0ff;"></i>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;">QR Code</div>
                                        <div style="font-size:11px;color:#3a4d66;">Scan QR to pay with USDT</div>
                                    </div>
                                    <i class="bi bi-check-circle-fill xd-pm-check" style="font-size:17px;color:#f5a623;opacity:0;"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Payment Details: E-Wallet --}}
                    <div id="payment-details-ewallet" class="xd-pdetail active mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:16px;">
                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:14px;">USDT</div>

                        <div style="background:rgba(255,255,255,.03);border:1px solid #1a2235;border-radius:10px;overflow:hidden;margin-bottom:12px;">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding:12px 14px;border-bottom:1px solid #1a2235;">
                                <span style="font-size:12px;color:#3a4d66;flex-shrink:0;padding-top:1px;">Jaringan</span>
                                <div style="display:flex;align-items:center;gap:8px;flex:1;justify-content:flex-end;">
                                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;text-align:right;word-break:break-all;">{{ $walletNumber }}</span>
                                    <button type="button" onclick="copyText('{{ $walletNumber }}')" style="background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.22);border-radius:6px;padding:4px 8px;color:#f5a623;font-size:12px;cursor:pointer;flex-shrink:0;">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding:12px 14px;">
                                <span style="font-size:12px;color:#3a4d66;flex-shrink:0;padding-top:1px;">Alamat Setoran</span>
                                <div style="display:flex;align-items:center;gap:8px;flex:1;justify-content:flex-end;">
                                    <span style="font-size:12px;font-weight:700;color:#e2eaf8;font-family:monospace;text-align:right;word-break:break-all;">{{ $walletName }}</span>
                                    <button type="button" onclick="copyText('{{ $walletName }}')" style="background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.22);border-radius:6px;padding:4px 8px;color:#f5a623;font-size:12px;cursor:pointer;flex-shrink:0;">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div style="background:rgba(100,160,255,.07);border:1px solid rgba(100,160,255,.18);border-radius:10px;padding:10px 12px;display:flex;align-items:flex-start;gap:8px;">
                            <i class="bi bi-info-circle-fill" style="color:#64a0ff;font-size:13px;flex-shrink:0;margin-top:1px;"></i>
                            <span style="font-size:12px;color:#64a0ff;line-height:1.5;">Transfer USDT sesuai nominal yang tertera menggunakan network TRC20 dan BEP 20. Lalu upload bukti transfer</span>
                        </div>
                    </div>

                    {{-- Payment Details: QR Code --}}
                    <div id="payment-details-qrcode" class="xd-pdetail mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:16px;">
                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:14px;text-align:center;">Scan QR Code</div>
                        <div style="display:flex;justify-content:center;margin-bottom:14px;">
                            <div style="background:#fff;border-radius:12px;padding:12px;display:inline-block;">
                                <img src="{{ $qrCode }}" alt="QR Code" style="width:180px;height:180px;display:block;">
                            </div>
                        </div>
                        <div style="background:rgba(100,160,255,.07);border:1px solid rgba(100,160,255,.18);border-radius:10px;padding:10px 12px;display:flex;align-items:flex-start;gap:8px;">
                            <i class="bi bi-info-circle-fill" style="color:#64a0ff;font-size:13px;flex-shrink:0;margin-top:1px;"></i>
                            <span style="font-size:12px;color:#64a0ff;line-height:1.5;">Scan QR code dengan aplikasi crypto wallet Anda (TRC20 Network) dan upload bukti transfer</span>
                        </div>
                    </div>

                    {{-- Upload Proof --}}
                    <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:16px;padding:16px;">
                        <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:12px;">Upload Proof of Transfer</div>
                        <div onclick="document.getElementById('file-upload').click()" style="border:2px dashed #1a2235;border-radius:12px;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s;" onmouseover="this.style.borderColor='rgba(245,166,35,.35)'" onmouseout="this.style.borderColor='#1a2235'">
                            <input type="file" name="payment_proof" id="file-upload" accept="image/*" style="display:none;" onchange="handleFileUpload(event)" required>
                            <div id="upload-placeholder">
                                <i class="bi bi-cloud-upload" style="font-size:32px;color:#3a4d66;display:block;margin-bottom:8px;"></i>
                                <div style="font-size:13px;color:#e2eaf8;margin-bottom:4px;">Click to upload</div>
                                <div style="font-size:11px;color:#3a4d66;">PNG, JPG up to 5MB</div>
                            </div>
                            <div id="upload-preview" style="display:none;">
                                <img id="preview-image" src="" alt="Preview" style="max-width:100%;max-height:200px;border-radius:8px;margin-bottom:8px;">
                                <div style="font-size:12px;color:#7a8fad;" id="file-name"></div>
                            </div>
                        </div>
                        @error('payment_proof')
                            <div style="font-size:12px;color:#f04f5a;margin-top:6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <button type="button" onclick="goToStep1()" style="background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:12px;padding:13px;font-size:13px;font-weight:600;color:#7a8fad;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                        <button type="button" onclick="submitDeposit()" style="background:linear-gradient(135deg,#f5a623 0%,#e08800 100%);border:none;border-radius:12px;padding:13px;font-size:13px;font-weight:700;color:#080b12;cursor:pointer;box-shadow:0 4px 14px rgba(245,166,35,.25);">
                            Submit
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <style>
        .xd-step { display: none; }
        .xd-step.active { display: block; }
        .xd-pdetail { display: none; }
        .xd-pdetail.active { display: block; }
    </style>

    <script>
        let selectedFile = null;

        @if (session('success')) alert('{{ session('success') }}'); @endif
        @if (session('error'))   alert('{{ session('error') }}');   @endif
        @if ($errors->any())     alert('{{ $errors->first() }}');   @endif

        function goToStep2() {
            const amount = document.getElementById('deposit-amount').value;
            if (!amount || amount <= 0) { alert('Please enter a valid amount'); return; }
            if (amount < 10) { alert('Minimum deposit amount is 10 USDT'); return; }

            document.getElementById('summary-amount').textContent = parseFloat(amount).toFixed(2) + ' USDT';
            document.getElementById('form-amount').value = amount;

            document.getElementById('step-1').classList.remove('active');
            document.getElementById('step-2').classList.add('active');

            // Step indicator
            const s1 = document.getElementById('step-1-indicator');
            const s2 = document.getElementById('step-2-indicator');
            s1.querySelector('.xd-step-circle').style.background = '#00d48a';
            s1.querySelector('.xd-step-circle').innerHTML = '<i class="bi bi-check" style="font-size:14px;color:#080b12;font-weight:900;"></i>';
            s1.querySelector('div:last-child').style.color = '#00d48a';
            s2.querySelector('.xd-step-circle').style.background = '#f5a623';
            s2.querySelector('.xd-step-circle').style.borderColor = '#f5a623';
            s2.querySelector('.xd-step-circle').style.color = '#080b12';
            s2.querySelector('div:last-child').style.color = '#f5a623';
            document.getElementById('step-line').style.width = '100%';

            document.querySelector('.scrollable-content').scrollTop = 0;
        }

        function goToStep1() {
            document.getElementById('step-2').classList.remove('active');
            document.getElementById('step-1').classList.add('active');

            const s1 = document.getElementById('step-1-indicator');
            const s2 = document.getElementById('step-2-indicator');
            s1.querySelector('.xd-step-circle').style.background = '#f5a623';
            s1.querySelector('.xd-step-circle').style.color = '#080b12';
            s1.querySelector('.xd-step-circle').innerHTML = '1';
            s1.querySelector('div:last-child').style.color = '#f5a623';
            s2.querySelector('.xd-step-circle').style.background = '#1a2235';
            s2.querySelector('.xd-step-circle').style.borderColor = '#1a2235';
            s2.querySelector('.xd-step-circle').style.color = '#3a4d66';
            s2.querySelector('div:last-child').style.color = '#3a4d66';
            document.getElementById('step-line').style.width = '0%';

            document.querySelector('.scrollable-content').scrollTop = 0;
        }

        function selectMethod(method) {
            document.getElementById('form-payment-method').value = method;
            document.querySelectorAll('.xd-pm-check').forEach(c => c.style.opacity = '0');
            document.querySelectorAll('.xd-pm-card').forEach(c => {
                c.style.background = 'rgba(255,255,255,.03)';
                c.style.borderColor = '#1a2235';
            });
            document.querySelectorAll('.xd-pdetail').forEach(d => d.classList.remove('active'));

            if (method === 'ewallet') {
                document.getElementById('method-ewallet').checked = true;
                document.getElementById('payment-details-ewallet').classList.add('active');
                const card = document.getElementById('method-ewallet').nextElementSibling;
                card.style.background = 'rgba(245,166,35,.06)';
                card.style.borderColor = 'rgba(245,166,35,.35)';
                card.querySelector('.xd-pm-check').style.opacity = '1';
            } else {
                document.getElementById('method-qrcode').checked = true;
                document.getElementById('payment-details-qrcode').classList.add('active');
                const card = document.getElementById('method-qrcode').nextElementSibling;
                card.style.background = 'rgba(245,166,35,.06)';
                card.style.borderColor = 'rgba(245,166,35,.35)';
                card.querySelector('.xd-pm-check').style.opacity = '1';
            }
        }

        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.createElement('div');
                toast.style.cssText = 'position:fixed;top:20px;right:20px;background:#00d48a;color:#080b12;padding:10px 18px;border-radius:10px;z-index:9999;font-size:13px;font-weight:700;box-shadow:0 4px 16px rgba(0,0,0,.4);';
                toast.textContent = 'Copied!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 1800);
            });
        }

        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) { alert('File size must not exceed 5MB'); event.target.value = ''; return; }
            const allowed = ['image/jpeg','image/png','image/jpg'];
            if (!allowed.includes(file.type)) { alert('Only JPG, JPEG, and PNG files are allowed'); event.target.value = ''; return; }
            selectedFile = file;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('upload-placeholder').style.display = 'none';
                document.getElementById('upload-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        function submitDeposit() {
            if (!document.getElementById('file-upload').files[0]) { alert('Please upload proof of transfer'); return; }
            if (confirm('Are you sure you want to submit this deposit request?')) {
                document.getElementById('deposit-form').submit();
            }
        }
    </script>
@endsection