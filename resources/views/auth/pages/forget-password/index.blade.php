<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}/" />
    <title>STARS INVESMENT - Lupa Password</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ $appConfig['app_logo']['value'] }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
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

        .fp-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT ── */
        .fp-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: #080b12;
        }

        .fp-box {
            width: 100%;
            max-width: 420px;
        }

        .fp-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .fp-logo img { height: 56px; }

        .fp-card {
            background: #0d1120;
            border: 1px solid #1a2235;
            border-radius: 20px;
            padding: 32px 28px;
            position: relative;
            overflow: hidden;
        }
        .fp-card::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,166,35,.08) 0%, transparent 65%);
            pointer-events: none;
        }
        .fp-card::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,212,138,.05) 0%, transparent 65%);
            pointer-events: none;
        }

        /* icon lock besar */
        .fp-icon-wrap {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: rgba(245,166,35,.1);
            border: 1px solid rgba(245,166,35,.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            color: #f5a623;
            margin-bottom: 16px;
        }

        .fp-title {
            font-size: 22px;
            font-weight: 700;
            color: #e2eaf8;
            letter-spacing: -.4px;
            margin-bottom: 6px;
        }
        .fp-subtitle {
            font-size: 12px;
            color: #3a4d66;
            line-height: 1.6;
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

        .input-wrap {
            position: relative;
            margin-bottom: 20px;
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
            border-color: #f5a623;
            box-shadow: 0 0 0 3px rgba(245,166,35,.08);
        }
        .input-dark.is-invalid { border-color: #f04f5a; }

        .err-msg {
            font-size: 11px;
            color: #f04f5a;
            margin-top: -14px;
            margin-bottom: 14px;
        }

        /* alerts */
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

        /* submit btn - orange accent */
        .btn-send {
            width: 100%;
            background: linear-gradient(135deg, #f5a623 0%, #e8950f 100%);
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 14px;
            font-weight: 700;
            color: #080b12;
            cursor: pointer;
            letter-spacing: .3px;
            transition: opacity .2s, transform .1s;
            margin-bottom: 20px;
        }
        .btn-send:hover { opacity: .9; }
        .btn-send:active { transform: scale(.98); }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            color: #3a4d66;
            text-decoration: none;
            transition: color .2s;
        }
        .back-link:hover { color: #00d48a; }

        /* ── RIGHT ── */
        .fp-right {
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
        .fp-right::before {
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
        .right-title span { color: #f5a623; }

        .right-desc {
            font-size: 13px;
            color: #3a4d66;
            line-height: 1.65;
            margin-bottom: 32px;
        }

        .step-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-align: left;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,.03);
            border: 1px solid #1a2235;
            border-radius: 12px;
            padding: 12px 14px;
        }
        .step-num {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: rgba(245,166,35,.1);
            border: 1px solid rgba(245,166,35,.2);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 13px;
            font-weight: 700;
            color: #f5a623;
        }
        .step-text {
            font-size: 13px;
            font-weight: 600;
            color: #7a8fad;
        }

        @media (max-width: 768px) {
            .fp-right { display: none; }
        }
    </style>
</head>

<body>
    <div class="fp-wrapper">

        {{-- ── LEFT: Form ── --}}
        <div class="fp-left">
            <div class="fp-box">

                <div class="fp-logo">
                    <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" />
                </div>

                <div class="fp-card">

                    <div class="fp-icon-wrap">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    <div class="fp-title">Lupa Password?</div>
                    <div class="fp-subtitle">Masukkan email Anda dan kami akan mengirimkan kode OTP untuk mereset password.</div>

                    <form method="POST" action="{{ route('forget-password.send-otp') }}">
                        @csrf

                        @if (session('success'))
                            <div class="alert-dark-success">
                                <i class="bi bi-check-circle" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert-dark-danger">
                                <i class="bi bi-exclamation-circle" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

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

                        <label class="form-label-dark">Alamat Email</label>
                        <div class="input-wrap">
                            <i class="bi bi-envelope"></i>
                            <input type="email"
                                name="email"
                                autocomplete="off"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email Anda"
                                class="input-dark @error('email') is-invalid @enderror"
                                required />
                        </div>
                        @error('email')
                            <div class="err-msg">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn-send">
                            <i class="bi bi-send" style="margin-right:6px;"></i>
                            Kirim Kode OTP
                        </button>

                        <a href="{{ route('login') }}" class="back-link">
                            <i class="bi bi-arrow-left"></i>
                            Kembali ke Login
                        </a>

                    </form>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Visual ── --}}
        <div class="fp-right">
            <div class="right-content">

                <div class="right-logo">
                    <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" />
                </div>

                <div class="right-title">
                    Reset Password<br><span>Dengan Mudah</span>
                </div>
                <div class="right-desc">
                    Ikuti 3 langkah mudah untuk memulihkan akses ke akun Anda.
                </div>

                <div class="step-list">
                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-text">Masukkan email yang terdaftar</div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">2</div>
                        <div class="step-text">Cek email dan masukkan kode OTP</div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">3</div>
                        <div class="step-text">Buat password baru Anda</div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
</body>

</html>