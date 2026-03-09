<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TwoFactorController extends Controller
{
    /**
     * Show the two-factor challenge view.
     */
    public function index()
    {
        // Check if we have a user ID in session
        $userId = session('two_factor:user:id');

        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'No 2FA session found.');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Show 2FA settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.two-factor', compact('user'));
    }

    /**
     * Enable 2FA - Step 1: Send code
     */
    public function enable(Request $request)
    {
        $user = $request->user();

        Log::info('ENABLE 2FA - Start', [
            'user_id' => $user->id,
            'email' => $user->email,
            'current_status' => $user->two_factor_enabled
        ]);

        if ($user->two_factor_enabled) {
            return back()->with('error', '2FA is already enabled.');
        }

        // Generate and save code
        $code = sprintf("%06d", mt_rand(1, 999999));

        $user->two_factor_code = $code;
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();

        Log::info('ENABLE 2FA - Code saved', [
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => $user->two_factor_expires_at
        ]);

        // Send email
        try {
            Mail::to($user)->send(new TwoFactorCode($code));
            Log::info('ENABLE 2FA - Email sent');
        } catch (\Exception $e) {
            Log::error('ENABLE 2FA - Email failed: ' . $e->getMessage());
        }

        // Store in session
        session(['two_factor:enabling' => true]);
        session(['two_factor:user:id' => $user->id]);

        return redirect()->route('two-factor.challenge')
            ->with('status', 'Verification code sent to your email.');
    }

    /**
     * Verify 2FA code - Step 2: Verify and enable
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $userId = session('two_factor:user:id');
        $isEnabling = session('two_factor:enabling', false);

        Log::info('VERIFY 2FA - Start', [
            'user_id' => $userId,
            'is_enabling' => $isEnabling,
            'submitted_code' => $request->code
        ]);

        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please try again.');
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            Log::error('VERIFY 2FA - User not found');
            return redirect()->route('login')
                ->with('error', 'User not found.');
        }

        Log::info('VERIFY 2FA - User found', [
            'user_id' => $user->id,
            'email' => $user->email,
            'stored_code' => $user->two_factor_code,
            'stored_expires' => $user->two_factor_expires_at
        ]);

        // Check if code exists
        if (!$user->two_factor_code) {
            return redirect()->route('two-factor.challenge')
                ->with('error', 'No verification code found. Please request a new one.');
        }

        // Check if expired
        if (Carbon::parse($user->two_factor_expires_at)->isPast()) {
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();

            return redirect()->route('two-factor.challenge')
                ->with('error', 'Code expired. Please request a new one.');
        }

        // Verify code
        if ($request->code !== $user->two_factor_code) {
            Log::info('VERIFY 2FA - Invalid code');
            return back()->with('error', 'Invalid code.');
        }

        Log::info('VERIFY 2FA - Code valid');

        // If enabling 2FA
        if ($isEnabling) {
            Log::info('VERIFY 2FA - Enabling 2FA now');

            // Generate recovery codes
            $recoveryCodes = [];
            for ($i = 0; $i < 8; $i++) {
                $recoveryCodes[] = Str::upper(Str::random(8));
            }

            // IMPORTANT: Update the user directly with fresh instance
            $updated = \App\Models\User::where('id', $user->id)->update([
                'two_factor_enabled' => true,
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            Log::info('VERIFY 2FA - Database update result', [
                'rows_affected' => $updated,
                'user_id' => $user->id
            ]);

            // Clear session
            session()->forget('two_factor:enabling');
            session()->forget('two_factor:user:id');

            // Log user back in if needed
            if (!Auth::check()) {
                Auth::login($user);
            }

            // Fetch fresh user data to confirm
            $freshUser = \App\Models\User::find($user->id);
            Log::info('VERIFY 2FA - After update check', [
                'two_factor_enabled' => $freshUser->two_factor_enabled
            ]);

            return redirect()->route('profile.two-factor')
                ->with('success', '2FA enabled successfully!')
                ->with('recovery_codes', $recoveryCodes);
        }

        // Regular login flow
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        Auth::login($user);
        session()->forget('two_factor:user:id');

        return redirect()->intended('dashboard')
            ->with('success', 'Successfully authenticated.');
    }

    /**
     * Resend verification code
     */
    public function resend(Request $request)
    {
        $userId = session('two_factor:user:id');

        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Session expired.');
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found.');
        }

        // Generate new code
        $code = sprintf("%06d", mt_rand(1, 999999));

        $user->two_factor_code = $code;
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();

        // Send email
        Mail::to($user)->send(new TwoFactorCode($code));

        return redirect()->route('two-factor.challenge')
            ->with('status', 'New code sent to your email.');
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $user = $request->user();

        $user->two_factor_enabled = false;
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        return back()->with('success', '2FA disabled.');
    }
}
