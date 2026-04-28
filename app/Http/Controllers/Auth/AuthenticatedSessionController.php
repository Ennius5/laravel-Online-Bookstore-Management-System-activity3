<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode;
Use App\Services\AuditService;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log failed login for the email that attempted
            AuditService::log('login_failed', \App\Models\User::class, null, ['email' => $request->email]);
            throw $e;
        }

        $user = Auth::user();

        // Check if user has 2FA enabled
        if ($user->two_factor_enabled) {
            // Check if device is trusted
            if (!$this->isDeviceTrusted($user, $request)) {
                // Generate and send 2FA code
                $this->generateAndSendCode($user);

                // Logout the user temporarily
                Auth::logout();

                // Store user ID in session for 2FA verification
                $request->session()->put('two_factor:user:id', $user->id);

                return redirect()->route('two-factor.challenge');
            }
        }
        \App\Services\AuditService::log('login', 'App\Models\User', $user->id);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        \App\Services\AuditService::log('logout', 'App\Models\User', $user->id);


        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        return redirect('/');
    }


      /**
     * Check if device is trusted for 2FA.
     */
    private function isDeviceTrusted($user, $request)
    {
        $cookieName = 'two_factor_trust_' . md5($user->id);

        if ($request->hasCookie($cookieName)) {
            try {
                $data = json_decode(decrypt($request->cookie($cookieName)), true);

                if (isset($data['user_id']) && $data['user_id'] === $user->id) {
                    $expires = Carbon::createFromTimestamp($data['expires']);

                    if (!$expires->isPast()) {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                // Invalid cookie, ignore
            }
        }

        return false;
    }

    /**
     * Generate and send verification code.
     */
    private function generateAndSendCode($user)
    {
        // Generate 6-digit code
        $code = sprintf("%06d", mt_rand(1, 999999));

        // Save to database
        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        // Send email
        Mail::to($user)->send(new TwoFactorCode($code));
    }
}
