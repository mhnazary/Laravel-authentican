<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function showPrompt(Request $request): RedirectResponse|View
    {
        // If user is already verified, send them directly to the dashboard
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard'))
            : view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // Validate the 6-digit verification code input
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user    = $request->user();
        // Rate-limit OTP attempts per user to prevent brute-force.
        // Key is scoped to the user so it survives IP changes.
        $limiterKey = 'otp-verify:' . $user->id;

        if (RateLimiter::tooManyAttempts($limiterKey, 3)) {
            // Too many wrong attempts — wipe the code to force a resend.
            $user->forceFill([
                'verification_code'            => null,
                'verification_code_expires_at' => null,
            ])->save();

            RateLimiter::clear($limiterKey);

            throw \Illuminate\Validation\ValidationException::withMessages([
                'code' => ['Too many incorrect attempts. Please request a new verification code.'],
            ]);
        }

        // Compare the SHA-256 hash of the submitted code against the stored hash.
        // Codes are never stored in plaintext — only their SHA-256 digest is.
        $submittedHash = hash('sha256', $request->code);

        if ($user->verification_code !== $submittedHash ||
            now()->greaterThan($user->verification_code_expires_at)) {

            // Record the failed attempt
            RateLimiter::hit($limiterKey, 60);

            throw \Illuminate\Validation\ValidationException::withMessages([
                'code' => ['The verification code is invalid or has expired.'],
            ]);
        }

        // Success — clear the rate limiter and mark the user as verified.
        RateLimiter::clear($limiterKey);

        $user->forceFill([
            'email_verified_at'            => now(),
            'verification_code'            => null,
            'verification_code_expires_at' => null,
        ])->save();

        // Fire standard verified event
        event(new \Illuminate\Auth\Events\Verified($user));

        return redirect()->route('dashboard')
            ->with('success', 'Email verified successfully! Welcome to your dashboard.');
    }

    /**
     * Send a new email verification notification.
     */
    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // Send standard notification
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
