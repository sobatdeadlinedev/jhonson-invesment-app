<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}/" />
    <title>STARS INVESMENT - Verifikasi OTP</title>
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
        @keyframes pulse-ring {
            0% { transform: scale(.9); opacity:.6; }
            50% { transform: scale(1.05); opacity:1; }
            100% { transform: scale(.9); opacity:.6; }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #080b12;
            font-family: 'Inter', sans-serif;
            color: #e2eaf8;
        }

        .otp-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT ── */
        .otp-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: #080b12;
        }

        .otp-box {
            width: 100%;
            max-width: 420px;
        }

        .otp-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .otp-logo img { height: 56px; }

        .otp-card {
            background: #0d1120;
            border: 1px solid #1a2235;
            border-radius: 20px;
            padding: 36px 28px;
            position: relative;
            overflow: hidden;
        }
        .otp-card::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,212,138,.08) 0%, transparent 65%);
            pointer-events: none;
        }
        .otp-card::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,166,35,.05) 0%, transparent 65%);
            pointer-events: none;
        }

        /* icon envelope animasi */
        .otp-icon-wrap {
            width: 64px; height: 64px;
            border-radius: 18px;
            background: rgba(0,212,138,.1);
            border: 1px solid rgba(0,212,138,.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
            color: #00d48a;
            margin: 0 auto 20px;
            animation: pulse-ring 2.5s ease-in-out infinite;
        }

        .otp-title {
            font-size: 22px;
            font-weight: 700;
            color: #e2eaf8;
            letter-spacing: -.4px;
            margin-bottom: 8px;
            text-align: center;
        }
        .otp-subtitle {
            font-size: 12px;
            color: #3a4d66;
            line-height: 1.65;
            margin-bottom: 28px;
            text-align: center;
        }
        .otp-subtitle strong {
            color: #00d48a;
            font-weight: 600;
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

        /* OTP input besar */
        .otp-input-wrap {
            margin-bottom: 8px;
        }
        .otp-input {
            width: 100%;
            background: #0a0f1a;
            border: 2px solid #1a2235;
            border-radius: 14px;
            padding: 18px 14px;
            font-size: 32px;
            font-weight: 700;
            font-family: monospace;
            color: #00d48a;
            text-align: center;
            outline: none;
            letter-spacing: 12px;
            transition: border-color .2s, box-shadow .2s;
        }
        .otp-input::placeholder {
            color: #1a2235;
            font-size: 24px;
            letter-spacing: 8px;
        }
        .otp-input:focus {
            border-color: #00d48a;
            box-shadow: 0 0 0 3px rgba(0,212,138,.08);
        }
        .otp-input.is-invalid { border-color: #f04f5a; }

        .err-msg {
            font-size: 11px;
            color: #f04f5a;
            margin-bottom: 14px;
            text-align: center;
        }

        .otp-hint {
            font-size: 11px;
            color: #3a4d66;
            text-align: center;
            margin-bottom: 20px;
        }

        /* submit btn */
        .btn-verify {
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
            margin-bottom: 20px;
        }
        .btn-verify:hover { opacity: .9; }
        .btn-verify:active { transform: scale(.98); }

        .resend-row {
            text-align: center;
            font-size: 12px;
            color: #3a4d66;
        }
        .resend-row a {
            color: #f5a623;
            font-weight: 700;
            text-decoration: none;
        }
        .resend-row a:hover { text-decoration: underline; }

        /* ── RIGHT ── */
        .otp-right {
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
        .otp-right::before {
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

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-align: left;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,.03);
            border: 1px solid #1a2235;
            border-radius: 12px;
            padding: 12px 14px;
        }
        .info-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: rgba(0,212,138,.1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
            color: #00d48a;
        }
        .info-text {
            font-size: 13px;
            font-weight: 600;
            color: #7a8fad;
        }

        @media (max-width: 768px) {
            .otp-right { display: none; }
        }
    </style>
</head>

<body>
    <div class="otp-wrapper">

        {{-- ── LEFT: Form ── --}}
        <div class="otp-left">
            <div class="otp-box">

                <div class="otp-logo">
                    <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" />
                </div>

                <div class="otp-card">

                    <div class="otp-icon-wrap">
                        <i class="bi bi-envelope-check"></i>
                    </div>

                    <div class="otp-title">Verifikasi OTP</div>
                    <div class="otp-subtitle">
                        Kode OTP telah dikirim ke<br>
                        <strong>{{ session('email') }}</strong>
                    </div>

                    <form method="POST" action="{{ route('verify-otp.post') }}">
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

                        <div class="otp-input-wrap">
                            <input type="text"
                                name="otp"
                                autocomplete="off"
                                maxlength="6"
                                pattern="[0-9]{6}"
                                placeholder="······"
                                class="otp-input @error('otp') is-invalid @enderror"
                                required />
                        </div>
                        @error('otp')
                            <div class="err-msg">{{ $message }}</div>
                        @enderror

                        <div class="otp-hint">Masukkan 6 digit kode yang dikirim ke email Anda</div>

                        <button type="submit" class="btn-verify">
                            <i class="bi bi-shield-check" style="margin-right:6px;"></i>
                            Verifikasi OTP
                        </button>

                        <div class="resend-row">
                            Tidak menerima kode?
                            <a href="{{ route('forget-password') }}">Kirim Ulang</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Visual ── --}}
        <div class="otp-right">
            <div class="right-content">

                <div class="right-logo">
                    <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" />
                </div>

                <div class="right-title">
                    Satu Langkah<br><span>Lagi!</span>
                </div>
                <div class="right-desc">
                    Masukkan kode OTP untuk memverifikasi identitas Anda dan lanjutkan proses reset password.
                </div>

                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-clock"></i></div>
                        <div class="info-text">Kode berlaku selama 10 menit</div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-envelope"></i></div>
                        <div class="info-text">Cek folder spam jika tidak masuk inbox</div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-shield-lock"></i></div>
                        <div class="info-text">Jangan bagikan kode OTP kepada siapapun</div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
</body>

</html>