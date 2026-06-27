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
        RateLimiter::for('checkout', function ($request) {
            return Limit::perMinute(10)->by($request->user()->id)
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many requests, slow down'
                    ], 429);
                });
        });

        RateLimiter::for('cart', function ($request) {
            return Limit::perMinute(30)->by($request->user()->id)
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many requests, slow down'
                    ], 429);
                });
        });
    }
}
