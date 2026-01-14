<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}/" />
    <title>STARS INVESMENT - Register</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>

<body id="kt_body" class="app-blank">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid" style="min-height: 100vh;">
            <!-- Left Section - Register Form -->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 order-2 order-lg-1" style="overflow-y: auto;">
                <div class="d-flex flex-center flex-column w-100 p-5 p-lg-10">
                    <div class="w-100 w-lg-500px">
                        <!-- Logo -->
                        <div class="text-center mb-7">
                            <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" class="h-60px mb-4" />
                        </div>

                        <!-- Title -->
                        <div class="text-center mb-8">
                            <h1 class="text-gray-900 fw-bolder mb-2 fs-2x">Create New Account</h1>
                            <div class="text-gray-500 fw-semibold fs-7">Sign up to start trading with STARS INVESTMENT
                            </div>
                        </div>

                        <!-- Register Form -->
                        <form class="form w-100" method="POST" action="{{ route('register.post') }}">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger d-flex align-items-center p-4 mb-7">
                                    <i class="ki-duotone ki-information fs-2x text-danger me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div class="d-flex flex-column">
                                        <h5 class="mb-1 text-danger fs-7">Error</h5>
                                        @foreach ($errors->all() as $error)
                                            <span class="fs-8">{{ $error }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Referral Code Info (if exists) -->
                            @if ($referralCode && $referrer)
                                <div class="alert alert-success d-flex align-items-center p-4 mb-7">
                                    <i class="ki-duotone ki-shield-tick fs-2x text-success me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <div class="d-flex flex-column">
                                        <h5 class="mb-1 text-success fs-7">Referral Code Detected</h5>
                                        <span class="fs-8 text-success">You will be registered as a referral from
                                            <strong>{{ $referrer->username }}</strong></span>
                                    </div>
                                </div>
                            @endif

                            <!-- Full Name Input -->
                            <div class="fv-row mb-6">
                                <label class="form-label fs-7 fw-bolder text-gray-900">Full Name</label>
                                <div class="position-relative">
                                    <i class="ki-duotone ki-user fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" placeholder="Enter your full name" name="name"
                                        autocomplete="off" value="{{ old('name') }}"
                                        class="form-control bg-transparent ps-13 @error('name') is-invalid @enderror"
                                        required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Username Input -->
                            <div class="fv-row mb-6">
                                <label class="form-label fs-7 fw-bolder text-gray-900">Username</label>
                                <div class="position-relative">
                                    <i
                                        class="ki-duotone ki-profile-user fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    <input type="text" placeholder="Choose a username" name="username"
                                        autocomplete="off" value="{{ old('username') }}"
                                        class="form-control bg-transparent ps-13 @error('username') is-invalid @enderror"
                                        required />
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Input -->
                            <div class="fv-row mb-6">
                                <label class="form-label fs-7 fw-bolder text-gray-900">Email Address</label>
                                <div class="position-relative">
                                    <i class="ki-duotone ki-sms fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="email" placeholder="Enter your email" name="email"
                                        autocomplete="off" value="{{ old('email') }}"
                                        class="form-control bg-transparent ps-13 @error('email') is-invalid @enderror"
                                        required />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone Input -->
                            <div class="fv-row mb-6">
                                <label class="form-label fs-7 fw-bolder text-gray-900">Phone Number</label>
                                <div class="position-relative">
                                    <i
                                        class="ki-duotone ki-phone fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" placeholder="08xxxxxxxxxx" name="phone" autocomplete="off"
                                        value="{{ old('phone') }}"
                                        class="form-control bg-transparent ps-13 @error('phone') is-invalid @enderror"
                                        required />
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text fs-8 text-muted">Number will be automatically formatted to 62xxx
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="fv-row mb-6">
                                <label class="form-label fw-bolder text-gray-900 fs-7 mb-0">Password</label>
                                <div class="position-relative mb-1">
                                    <i
                                        class="ki-duotone ki-lock fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="password" placeholder="Create a password" name="password"
                                        id="password" autocomplete="off"
                                        class="form-control bg-transparent ps-13 @error('password') is-invalid @enderror"
                                        required />
                                    <button type="button"
                                        class="btn btn-sm btn-icon position-absolute top-50 translate-middle-y end-0 me-2"
                                        onclick="togglePassword('password', 'password-icon')" style="z-index: 10;">
                                        <i class="ki-duotone ki-eye fs-2" id="password-icon">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text fs-8 text-muted">Minimum 8 characters</div>
                            </div>

                            <!-- Confirm Password Input -->
                            <div class="fv-row mb-6">
                                <label class="form-label fw-bolder text-gray-900 fs-7 mb-0">Confirm Password</label>
                                <div class="position-relative">
                                    <i
                                        class="ki-duotone ki-lock fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="password" placeholder="Confirm your password"
                                        name="password_confirmation" id="password_confirmation" autocomplete="off"
                                        class="form-control bg-transparent ps-13" required />
                                    <button type="button"
                                        class="btn btn-sm btn-icon position-absolute top-50 translate-middle-y end-0 me-2"
                                        onclick="togglePassword('password_confirmation', 'password-confirmation-icon')"
                                        style="z-index: 10;">
                                        <i class="ki-duotone ki-eye fs-2" id="password-confirmation-icon">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>

                            <!-- Referral Code Input -->
                            <div class="fv-row mb-7">
                                <label class="form-label fs-7 fw-bolder text-gray-900">Referral Code (Optional)</label>
                                <div class="position-relative">
                                    <i
                                        class="ki-duotone ki-gift fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    <input type="text" placeholder="Enter referral code" name="referral_code"
                                        autocomplete="off" value="{{ old('referral_code', $referralCode ?? '') }}"
                                        class="form-control bg-transparent ps-13 @error('referral_code') is-invalid @enderror"
                                        {{ $referralCode ? 'readonly' : '' }} />
                                    @error('referral_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text fs-8 text-muted">Enter referral code if you have one</div>
                            </div>

                            <!-- Sign Up Button -->
                            <div class="d-grid mb-7">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">
                                        Create Account
                                        <i class="ki-duotone ki-arrow-right fs-3 ms-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </button>
                            </div>

                            <!-- Sign In Link -->
                            <div class="text-gray-500 text-center fw-semibold fs-7 mb-5">
                                Already have an account?
                                <a href="{{ route('login') }}" class="link-primary fw-bold">Sign in</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Section - Visual -->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
                style="background-image: url(assets/media/misc/auth-bg.png); min-height: 100vh;">
                <div class="d-flex flex-column flex-center py-10 px-5 px-md-10 w-100">
                    <!-- Logo -->
                    <a href="#" class="mb-8">
                        <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}"
                            class="h-120px h-lg-150px" />
                    </a>

                    <!-- Title & Description -->
                    <h1 class="text-white fs-2x fw-bolder text-center mb-5">
                        Start Trading Today
                    </h1>
                    <div class="text-white fs-6 text-center fw-semibold mb-8" style="max-width: 500px;">
                        Join millions of traders worldwide. Trade cryptocurrencies with confidence on our secure
                        platform.
                    </div>

                    <!-- Features List -->
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-25px me-4">
                                <span class="symbol-label bg-white bg-opacity-20">
                                    <i class="ki-duotone ki-check fs-4 text-white">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <span class="text-white fw-semibold fs-6">Secure & Reliable Platform</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-25px me-4">
                                <span class="symbol-label bg-white bg-opacity-20">
                                    <i class="ki-duotone ki-check fs-4 text-white">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <span class="text-white fw-semibold fs-6">24/7 Customer Support</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-25px me-4">
                                <span class="symbol-label bg-white bg-opacity-20">
                                    <i class="ki-duotone ki-check fs-4 text-white">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <span class="text-white fw-semibold fs-6">Low Trading Fees</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var hostUrl = "assets/";

        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<span class="path1"></span><span class="path2"></span>';
                eyeIcon.classList.remove('ki-eye');
                eyeIcon.classList.add('ki-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<span class="path1"></span><span class="path2"></span><span class="path3"></span>';
                eyeIcon.classList.remove('ki-eye-slash');
                eyeIcon.classList.add('ki-eye');
            }
        }
    </script>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
</body>

</html>
