<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function ($request) {
            $email = (string) $request->email;

            return Limit::perMinute(3)->by($email . $request->ip())
                ->response(function () {
                    return apiResponse(429, "Too Many Login Attempts. Please try again later.");
                });
        });
    }
}
