<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}/" />
    <title>STARS INVESMENT - Register</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ $appConfig['app_logo']['value'] }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
    <style>
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.15} }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #080b12;
            font-family: 'Inter', sans-serif;
            color: #e2eaf8;
        }

        .register-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT ── */
        .register-left {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 20px;
            background: #080b12;
            overflow-y: auto;
        }

        .register-box {
            width: 100%;
            max-width: 460px;
            padding: 10px 0 40px;
        }

        .register-logo {
            text-align: center;
            margin-bottom: 24px;
        }
        .register-logo img { height: 56px; }

        .register-card {
            background: #0d1120;
            border: 1px solid #1a2235;
            border-radius: 20px;
            padding: 32px 28px;
            position: relative;
            overflow: hidden;
        }
        .register-card::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,212,138,.08) 0%, transparent 65%);
            pointer-events: none;
        }
        .register-card::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,166,35,.05) 0%, transparent 65%);
            pointer-events: none;
        }

        .register-title {
            font-size: 22px;
            font-weight: 700;
            color: #e2eaf8;
            letter-spacing: -.4px;
            margin-bottom: 4px;
        }
        .register-subtitle {
            font-size: 12px;
            color: #3a4d66;
            margin-bottom: 24px;
        }

        .form-label-dark {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #7a8fad;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }
        .form-label-dark .req { color: #f04f5a; }

        /* input wrap */
        .input-wrap {
            position: relative;
            margin-bottom: 16px;
        }
        .input-wrap > .bi {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 15px;
            color: #3a4d66;
            pointer-events: none;
        }
        .input-dark {
            width: 100%;
            background: #0a0f1a;
            border: 1px solid #1a2235;
            border-radius: 12px;
            padding: 12px 14px 12px 40px;
            font-size: 13px;
            color: #e2eaf8;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-dark::placeholder { color: #3a4d66; }
        .input-dark:focus {
            border-color: #00d48a;
            box-shadow: 0 0 0 3px rgba(0,212,138,.08);
        }
        .input-dark.is-invalid { border-color: #f04f5a; }
        .input-dark[readonly] {
            opacity: .6;
            cursor: not-allowed;
        }

        /* password wrap */
        .pw-wrap {
            position: relative;
            margin-bottom: 16px;
        }
        .pw-wrap input {
            width: 100%;
            background: #0a0f1a;
            border: 1px solid #1a2235;
            border-radius: 12px;
            padding: 12px 44px 12px 40px;
            font-size: 13px;
            color: #e2eaf8;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
        }
        .pw-wrap input::placeholder { color: #3a4d66; }
        .pw-wrap input:focus {
            border-color: #00d48a;
            box-shadow: 0 0 0 3px rgba(0,212,138,.08);
        }
        .pw-wrap input.is-invalid { border-color: #f04f5a; }
        .pw-icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #3a4d66;
            font-size: 15px;
            pointer-events: none;
            z-index: 2;
        }
        .pw-eye {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #7a8fad;
            font-size: 16px;
            padding: 0;
            line-height: 1;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pw-eye:hover { color: #00d48a; }

        .err-msg {
            font-size: 11px;
            color: #f04f5a;
            margin-top: -10px;
            margin-bottom: 12px;
        }

        .hint-msg {
            font-size: 11px;
            color: #3a4d66;
            margin-top: -10px;
            margin-bottom: 12px;
        }

        /* alert */
        .alert-dark-danger {
            background: rgba(240,79,90,.08);
            border: 1px solid rgba(240,79,90,.25);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 12px;
            color: #f04f5a;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
        .alert-dark-success {
            background: rgba(0,212,138,.06);
            border: 1px solid rgba(0,212,138,.2);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 12px;
            color: #00d48a;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
        .alert-dark-success strong { color: #e2eaf8; }

        /* submit btn */
        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #00d48a 0%, #00b876 100%);
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 14px;
            font-weight: 700;
            color: #080b12;
            cursor: pointer;
            letter-spacing: .3px;
            transition: opacity .2s, transform .1s;
            margin-bottom: 18px;
        }
        .btn-register:hover { opacity: .9; }
        .btn-register:active { transform: scale(.98); }

        .login-row {
            text-align: center;
            font-size: 12px;
            color: #3a4d66;
        }
        .login-row a {
            color: #00d48a;
            font-weight: 700;
            text-decoration: none;
        }
        .login-row a:hover { text-decoration: underline; }

        .live-dot {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(0,212,138,.07);
            border: 1px solid rgba(0,212,138,.18);
            border-radius: 99px;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: 700;
            color: #00d48a;
            letter-spacing: .6px;
            margin-bottom: 24px;
        }
        .live-dot span {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: #00d48a;
            animation: blink 1.6s infinite;
        }

        .field-group { margin-bottom: 20px; }

        /* ── RIGHT ── */
        .register-right {
            flex: 1;
            background: linear-gradient(135deg, #0f1c2e 0%, #0d1420 60%, #0a1118 100%);
            border-left: 1px solid #1a2235;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .register-right::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,212,138,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,138,.03) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        .right-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 380px;
        }

        .right-logo {
            animation: float 4s ease-in-out infinite;
            margin-bottom: 28px;
        }
        .right-logo img { height: 90px; }

        .right-title {
            font-size: 26px;
            font-weight: 700;
            color: #e2eaf8;
            letter-spacing: -.5px;
            margin-bottom: 10px;
            line-height: 1.25;
        }
        .right-title span { color: #00d48a; }

        .right-desc {
            font-size: 13px;
            color: #3a4d66;
            line-height: 1.65;
            margin-bottom: 32px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-align: left;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,.03);
            border: 1px solid #1a2235;
            border-radius: 12px;
            padding: 12px 14px;
        }
        .feature-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: rgba(0,212,138,.1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
            color: #00d48a;
        }
        .feature-text {
            font-size: 13px;
            font-weight: 600;
            color: #7a8fad;
        }

        @media (max-width: 768px) {
            .register-right { display: none; }
            .register-left { align-items: flex-start; }
        }
    </style>
</head>

<body>
    <div class="register-wrapper">

        {{-- ── LEFT: Form ── --}}
        <div class="register-left">
            <div class="register-box">

                <div class="register-logo">
                    <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" />
                </div>

                <div class="register-card">

                    <div class="live-dot">
                        <span></span> DAFTAR SEKARANG
                    </div>

                    <div class="register-title">Buat Akun Baru</div>
                    <div class="register-subtitle">Daftar untuk mulai trading di STARS INVESTMENT</div>

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        @if ($errors->any())
                            <div class="alert-dark-danger">
                                <i class="bi bi-shield-exclamation" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($referralCode && $referrer)
                            <div class="alert-dark-success">
                                <i class="bi bi-shield-check" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
                                <div>
                                    <div style="font-weight:700;margin-bottom:2px;">Kode Referral Terdeteksi</div>
                                    <div>Anda akan terdaftar sebagai referral dari <strong>{{ $referrer->username }}</strong></div>
                                </div>
                            </div>
                        @endif

                        {{-- Full Name --}}
                        <div class="field-group">
                            <label class="form-label-dark">Nama Lengkap</label>
                            <div class="input-wrap">
                                <i class="bi bi-person"></i>
                                <input type="text"
                                    name="name"
                                    autocomplete="off"
                                    value="{{ old('name') }}"
                                    placeholder="Masukkan nama lengkap"
                                    class="input-dark @error('name') is-invalid @enderror"
                                    required />
                            </div>
                            @error('name')
                                <div class="err-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div class="field-group">
                            <label class="form-label-dark">Username</label>
                            <div class="input-wrap">
                                <i class="bi bi-at"></i>
                                <input type="text"
                                    name="username"
                                    autocomplete="off"
                                    value="{{ old('username') }}"
                                    placeholder="Pilih username"
                                    class="input-dark @error('username') is-invalid @enderror"
                                    required />
                            </div>
                            @error('username')
                                <div class="err-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="field-group">
                            <label class="form-label-dark">Alamat Email</label>
                            <div class="input-wrap">
                                <i class="bi bi-envelope"></i>
                                <input type="email"
                                    name="email"
                                    autocomplete="off"
                                    value="{{ old('email') }}"
                                    placeholder="Masukkan email"
                                    class="input-dark @error('email') is-invalid @enderror"
                                    required />
                            </div>
                            @error('email')
                                <div class="err-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="field-group">
                            <label class="form-label-dark">Nomor HP</label>
                            <div class="input-wrap">
                                <i class="bi bi-phone"></i>
                                <input type="text"
                                    name="phone"
                                    autocomplete="off"
                                    value="{{ old('phone') }}"
                                    placeholder="08xxxxxxxxxx"
                                    class="input-dark @error('phone') is-invalid @enderror"
                                    required />
                            </div>
                            @error('phone')
                                <div class="err-msg">{{ $message }}</div>
                            @enderror
                            <div class="hint-msg">Nomor akan otomatis diformat ke 62xxx</div>
                        </div>

                        {{-- Password --}}
                        <div class="field-group">
                            <label class="form-label-dark">Password</label>
                            <div class="pw-wrap">
                                <i class="bi bi-lock pw-icon-left"></i>
                                <input type="password"
                                    name="password"
                                    id="password"
                                    autocomplete="off"
                                    placeholder="Buat password"
                                    class="@error('password') is-invalid @enderror"
                                    required />
                                <button type="button" class="pw-eye" onclick="togglePassword('password', 'pw-icon-1')">
                                    <i class="bi bi-eye" id="pw-icon-1"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="err-msg">{{ $message }}</div>
                            @enderror
                            <div class="hint-msg">Minimal 8 karakter</div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="field-group">
                            <label class="form-label-dark">Konfirmasi Password</label>
                            <div class="pw-wrap">
                                <i class="bi bi-lock pw-icon-left"></i>
                                <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    autocomplete="off"
                                    placeholder="Ulangi password"
                                    required />
                                <button type="button" class="pw-eye" onclick="togglePassword('password_confirmation', 'pw-icon-2')">
                                    <i class="bi bi-eye" id="pw-icon-2"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Referral Code --}}
                        <div class="field-group">
                            <label class="form-label-dark">Kode Referral <span class="req">*</span></label>
                            <div class="input-wrap">
                                <i class="bi bi-gift"></i>
                                <input type="text"
                                    name="referral_code"
                                    autocomplete="off"
                                    value="{{ old('referral_code', $referralCode ?? '') }}"
                                    placeholder="Masukkan kode referral"
                                    class="input-dark @error('referral_code') is-invalid @enderror"
                                    {{ $referralCode ? 'readonly' : '' }}
                                    required />
                            </div>
                            @error('referral_code')
                                <div class="err-msg">{{ $message }}</div>
                            @enderror
                            <div class="hint-msg">Kode referral wajib diisi untuk mendaftar</div>
                        </div>

                        <button type="submit" class="btn-register">
                            Buat Akun &nbsp;<i class="bi bi-arrow-right-short" style="font-size:16px;vertical-align:middle;"></i>
                        </button>

                        <div class="login-row">
                            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Visual ── --}}
        <div class="register-right">
            <div class="right-content">

                <div class="right-logo">
                    <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" />
                </div>

                <div class="right-title">
                    Gabung &amp; Mulai<br><span>Trading Sekarang</span>
                </div>
                <div class="right-desc">
                    Bergabung bersama ribuan trader. Kelola aset kripto Anda dengan aman dan mudah di platform kami.
                </div>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                        <div class="feature-text">Platform Aman &amp; Terpercaya</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-headset"></i></div>
                        <div class="feature-text">Support 24/7 Siap Membantu</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="feature-text">Keuntungan Trading Real-time</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-people"></i></div>
                        <div class="feature-text">Program Referral Menguntungkan</div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>

    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
</body>

</html>