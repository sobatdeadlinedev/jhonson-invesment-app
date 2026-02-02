<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}/" />
    <title>STARS INVESMENT</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ $appConfig['app_logo']['value'] }}" />
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
            <!-- Left Section - Login Form -->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-5 p-lg-10 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="w-100 w-lg-450px p-5">
                        <!-- Logo -->
                        <div class="text-center mb-7">
                            <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" class="h-60px mb-4" />
                        </div>

                        <!-- Title -->
                        <div class="text-center mb-8">
                            <h1 class="text-gray-900 fw-bolder mb-2 fs-2x">Sign in to your Account</h1>
                            <div class="text-gray-500 fw-semibold fs-7">Enter your credentials to access your account
                            </div>
                        </div>

                        <!-- Login Form -->
                        <form class="form w-100" method="POST" action="{{ route('login.post') }}">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger d-flex align-items-center p-4 mb-7">
                                    <i class="ki-duotone ki-shield-tick fs-2x text-danger me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <div class="d-flex flex-column">
                                        <h5 class="mb-1 text-danger fs-7">Error</h5>
                                        @foreach ($errors->all() as $error)
                                            <span class="fs-8">{{ $error }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Username/Phone Input -->
                            <div class="fv-row mb-6">
                                <label class="form-label fs-7 fw-bolder text-gray-900">Username or Phone Number</label>
                                <div class="position-relative">
                                    <i class="ki-duotone ki-user fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" placeholder="Enter username or phone" name="login"
                                        autocomplete="off" value="{{ old('login') }}"
                                        class="form-control bg-transparent ps-13 @error('login') is-invalid @enderror"
                                        required />
                                    @error('login')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="fv-row mb-6">
                                <label class="form-label fw-bolder text-gray-900 fs-7 mb-0">Password</label>
                                <div class="position-relative mb-3">
                                    <i class="ki-duotone ki-lock fs-2 position-absolute top-50 translate-middle-y ms-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="password" placeholder="Enter your password" name="password"
                                        id="passwordInput" autocomplete="off"
                                        class="form-control bg-transparent ps-13 @error('password') is-invalid @enderror"
                                        required />
                                    <button type="button"
                                        class="btn btn-sm btn-icon position-absolute top-50 translate-middle-y end-0 me-2"
                                        onclick="togglePassword()" style="z-index: 10;">
                                        <i class="ki-duotone ki-eye fs-2" id="eyeIcon">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-7">
                                <a href="{{ route('forget-password') }}" class="link-primary fs-7">
                                    Forgot Password?
                                </a>
                            </div>

                            <!-- Sign In Button -->
                            <div class="d-grid mb-7">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">
                                        Sign In
                                        <i class="ki-duotone ki-arrow-right fs-3 ms-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </button>
                            </div>

                            <!-- Sign Up Link -->
                            <div class="text-gray-500 text-center fw-semibold fs-7">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="link-primary fw-bold">Sign up</a>
                            </div>
                        </form>

                        <!-- Security Notice -->
                        <div
                            class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-4 mt-7">
                            <i class="ki-duotone ki-shield-tick fs-2x text-primary me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <h5 class="text-gray-900 fw-bold fs-7 mb-1">Secure Connection</h5>
                                    <div class="fs-8 text-gray-700">Your data is encrypted and protected with
                                        industry-standard security.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section - Visual -->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
                style="background-image: url(assets/media/misc/auth-bg.png); min-height: 100vh;">
                <div class="d-flex flex-column flex-center py-10 px-5 px-md-10 w-100">
                    <!-- Logo -->
                    <a href="#" class="mb-8">
                        <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" class="h-120px h-lg-150px" />
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

        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');

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
