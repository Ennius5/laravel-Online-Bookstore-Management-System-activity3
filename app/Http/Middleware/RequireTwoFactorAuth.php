<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequireTwoFactorAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If user is not logged in, let them proceed to login
        if (!$user) {
            return $next($request);
        }

        // Check if user has 2FA enabled but not verified in this session
        if ($user->two_factor_enabled && !session('two_factor:verified')) {
            Log::info('2FA Middleware - User requires 2FA verification', ['user_id' => $user->id]);

            // Store the intended URL
            session(['two_factor:intended_url' => $request->url()]);

            // Redirect to 2FA challenge
            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
