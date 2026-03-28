<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}/" />
    <title>STARS INVESMENT</title>
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

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT ── */
        .login-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: #080b12;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-logo img { height: 56px; }

        .login-card {
            background: #0d1120;
            border: 1px solid #1a2235;
            border-radius: 20px;
            padding: 32px 28px;
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,212,138,.08) 0%, transparent 65%);
            pointer-events: none;
        }
        .login-card::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,166,35,.05) 0%, transparent 65%);
            pointer-events: none;
        }

        .login-title {
            font-size: 22px;
            font-weight: 700;
            color: #e2eaf8;
            letter-spacing: -.4px;
            margin-bottom: 4px;
        }
        .login-subtitle {
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

        /* username input */
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

        /* password input */
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

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 11px;
            color: #3a4d66;
            text-decoration: none;
            margin-bottom: 20px;
            transition: color .2s;
        }
        .forgot-link:hover { color: #00d48a; }

        .btn-signin {
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
        }
        .btn-signin:hover { opacity: .9; }
        .btn-signin:active { transform: scale(.98); }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0;
            font-size: 11px;
            color: #1a2235;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #1a2235;
        }

        .register-row {
            text-align: center;
            font-size: 12px;
            color: #3a4d66;
        }
        .register-row a {
            color: #00d48a;
            font-weight: 700;
            text-decoration: none;
        }
        .register-row a:hover { text-decoration: underline; }

        .alert-dark {
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

        .secure-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(0,212,138,.04);
            border: 1px solid rgba(0,212,138,.12);
            border-radius: 12px;
            padding: 10px 14px;
            margin-top: 18px;
            font-size: 11px;
            color: #3a4d66;
        }
        .secure-badge .bi { color: #00d48a; font-size: 14px; flex-shrink: 0; }

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

        /* ── RIGHT ── */
        .login-right {
            flex: 1;
            background: linear-gradient(135deg, #0f1c2e 0%, #0d1420 60%, #0a1118 100%);
            border-left: 1px solid #1a2235;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        .login-right::before {
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
            .login-right { display: none; }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">

        {{-- ── LEFT: Form ── --}}
        <div class="login-left">
            <div class="login-box">

                <div class="login-logo">
                    <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" />
                </div>

                <div class="login-card">

                    <div class="live-dot">
                        <span></span> STARS INVESTMENT
                    </div>

                    <div class="login-title">Masuk ke Akun Anda</div>
                    <div class="login-subtitle">Masukkan kredensial untuk mengakses dashboard</div>

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        @if ($errors->any())
                            <div class="alert-dark">
                                <i class="bi bi-shield-exclamation" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Username --}}
                        <label class="form-label-dark">Username atau Nomor HP</label>
                        <div class="input-wrap">
                            <i class="bi bi-person"></i>
                            <input type="text"
                                name="login"
                                autocomplete="off"
                                value="{{ old('login') }}"
                                placeholder="Masukkan username atau HP"
                                class="input-dark @error('login') is-invalid @enderror"
                                required />
                        </div>
                        @error('login')
                            <div class="err-msg">{{ $message }}</div>
                        @enderror

                        {{-- Password --}}
                        <label class="form-label-dark">Password</label>
                        <div class="pw-wrap">
                            <i class="bi bi-lock pw-icon-left"></i>
                            <input type="password"
                                name="password"
                                id="passwordInput"
                                autocomplete="off"
                                placeholder="Masukkan password"
                                required />
                            <button type="button" class="pw-eye" onclick="togglePassword()">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="err-msg">{{ $message }}</div>
                        @enderror

                        <a href="{{ route('forget-password') }}" class="forgot-link">Lupa password?</a>

                        <button type="submit" class="btn-signin">
                            Masuk &nbsp;<i class="bi bi-arrow-right-short" style="font-size:16px;vertical-align:middle;"></i>
                        </button>

                        <div class="divider">atau</div>

                        <div class="register-row">
                            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
                        </div>
                    </form>

                    <div class="secure-badge">
                        <i class="bi bi-shield-check"></i>
                        <div>Koneksi aman — data Anda dienkripsi dan dilindungi.</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── RIGHT: Visual ── --}}
        <div class="login-right">
            <div class="right-content">

                <div class="right-logo">
                    <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" />
                </div>

                <div class="right-title">
                    Mulai Trading<br><span>Hari Ini</span>
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
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
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