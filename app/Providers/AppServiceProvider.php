<?php

namespace App\Providers;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
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
    RateLimiter::for('public', function (Request $request) {
    return Limit::perMinute(30)->by($request->ip());
});

RateLimiter::for('api', function (Request $request) {
    $user = $request->user();
    $limit = 60; // default standard customers

    if ($user) {
        if ($user->role === 'admin') {
            $limit = 1000;
        } elseif ($user->role === 'premium') {
            $limit = 300;
        }
    }

    return Limit::perMinute($limit)->by(optional($user)->id ?: $request->ip());
});

RateLimiter::for('admin', function (Request $request) {
    return Limit::perMinute(1000)->by($request->user()->id);
});
    }
}
