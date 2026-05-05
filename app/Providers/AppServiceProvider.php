<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\ConfigComposer;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ✅ Register User Observer
        User::observe(UserObserver::class);

        View::composer([
            'admin.layouts.app',
            'admin.components.sidebar',
            'member.components.header',
            'member.layouts.app',
            'auth.pages.login.index',
            'auth.pages.register.index',
            'auth.pages.forget-password.index',
            'auth.pages.forget-password.reset-password',
            'auth.pages.forget-password.verify-otp',
        ], ConfigComposer::class);
    }
}