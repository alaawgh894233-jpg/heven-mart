<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(100)->by(
                optional($request->user())->id ?: $request->ip()
            );
        });
    }
}
