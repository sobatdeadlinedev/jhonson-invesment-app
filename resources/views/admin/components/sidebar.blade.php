<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo flex-shrink-0 d-none d-md-flex align-items-center justify-content-center px-8"
        id="kt_app_sidebar_logo">
        <!--begin::Logo-->
        <a href="{{ route('admin.dashboard.index') }}">
            <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}"
                class="h-50px d-none d-sm-inline app-sidebar-logo-default theme-light-show" />
            <img alt="Logo" src="{{ $appConfig['app_logo']['value'] }}" class="h-50px h-lg-50px theme-dark-show" />
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
            <div class="btn btn-icon btn-active-color-primary w-30px h-30px" id="kt_aside_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-1"></i>
            </div>
        </div>
        <!--end::Aside toggle-->
    </div>

    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5 mx-3"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold px-1" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">

                <!--begin:Menu item - Dashboard-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-element-11 fs-2"></i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Section Header-->
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">User Management</span>
                    </div>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Users-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}"
                        href="{{ route('admin.user.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-profile-user fs-2"></i>
                        </span>
                        <span class="menu-title">Member List</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Verification-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.verification.*') ? 'active' : '' }}"
                        href="{{ route('admin.verification.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-shield-tick fs-2"></i>
                        </span>
                        <span class="menu-title">Verification</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Wallet-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.wallet.*') ? 'active' : '' }}"
                        href="{{ route('admin.wallet.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-wallet fs-2"></i>
                        </span>
                        <span class="menu-title">Wallet List</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Team-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}"
                        href="{{ route('admin.team.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-people fs-2"></i>
                        </span>
                        <span class="menu-title">Team List</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - User Level-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.user-levels.*') ? 'active' : '' }}"
                        href="{{ route('admin.user-levels.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-award fs-2"></i>
                        </span>
                        <span class="menu-title">User Level</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Referral Usage-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.refferal.*') ? 'active' : '' }}"
                        href="{{ route('admin.refferal.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-share fs-2"></i>
                        </span>
                        <span class="menu-title">Referral Usage</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Balance-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.balance.*') ? 'active' : '' }}"
                        href="{{ route('admin.balance.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-bill fs-2"></i>
                        </span>
                        <span class="menu-title">User Balance</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Section Header-->
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Trading</span>
                    </div>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Trading Signals-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.signals.*') ? 'active' : '' }}"
                        href="{{ route('admin.signals.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-chart-line-up fs-2"></i>
                        </span>
                        <span class="menu-title">Trading Signals</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Section Header-->
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Transactions</span>
                    </div>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Commission-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.commission.*') ? 'active' : '' }}"
                        href="{{ route('admin.commission.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-percentage fs-2"></i>
                        </span>
                        <span class="menu-title">Commission</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Deposit-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.deposit.*') ? 'active' : '' }}"
                        href="{{ route('admin.deposit.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-entrance-left fs-2"></i>
                        </span>
                        <span class="menu-title">Deposit</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Withdrawal-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.withdrawal.*') ? 'active' : '' }}"
                        href="{{ route('admin.withdrawal.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-exit-up fs-2"></i>
                        </span>
                        <span class="menu-title">Withdrawal</span>
                    </a>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Section Header-->
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Configuration</span>
                    </div>
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item - Configuration-->
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.config.*') ? 'active' : '' }}"
                        href="{{ route('admin.config.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-setting-2 fs-2"></i>
                        </span>
                        <span class="menu-title">Configuration</span>
                    </a>
                </div>
                <!--end:Menu item-->

            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

    <!--begin::Footer-->
    <div class="app-sidebar-footer d-flex align-items-center px-8 pb-10" id="kt_app_sidebar_footer">
        <!--begin::User-->
        <div class="">
            <!--begin::User info-->
            <div class="d-flex align-items-center" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-overflow="true" data-kt-menu-placement="top-start">
                <div class="d-flex flex-center cursor-pointer symbol symbol-circle symbol-40px">
                    <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="image" />
                </div>
                <!--begin::Name-->
                <div class="d-flex flex-column align-items-start justify-content-center ms-3">
                    <span class="text-gray-500 fs-8 fw-semibold">Admin</span>
                    <a href="#"
                        class="text-gray-800 fs-7 fw-bold text-hover-primary">{{ auth()->user()->name }}</a>
                </div>
                <!--end::Name-->
            </div>
            <!--end::User info-->

            <!--begin::User account menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                data-kt-menu="true">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <div class="menu-content d-flex align-items-center px-3">
                        <div class="symbol symbol-50px me-5">
                            <img alt="Logo" src="{{ asset('assets/media/avatars/blank.png') }}" />
                        </div>
                        <div class="d-flex flex-column">
                            <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}
                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Admin</span>
                            </div>
                            <a href="#"
                                class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                        </div>
                    </div>
                </div>
                <!--end::Menu item-->

                <div class="separator my-2"></div>

                <div class="menu-item px-5">
                    <a href="#" class="menu-link px-5">My Profile</a>
                </div>

                <div class="separator my-2"></div>

                <!--begin::Menu item - Theme Mode-->
                <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                    data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                    <a href="#" class="menu-link px-5">
                        <span class="menu-title position-relative">Mode
                            <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                            </span>
                        </span>
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                        data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-night-day fs-2"></i>
                                </span>
                                <span class="menu-title">Light</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-moon fs-2"></i>
                                </span>
                                <span class="menu-title">Dark</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-screen fs-2"></i>
                                </span>
                                <span class="menu-title">System</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Menu item - Theme Mode-->

                <!--begin::Menu item - Sign Out-->
                <div class="menu-item px-5">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="#" class="menu-link px-5"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign Out
                        </a>
                    </form>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::User account menu-->
        </div>
        <!--end::User-->
    </div>
    <!--end::Footer-->
</div>
<!--end::Sidebar-->