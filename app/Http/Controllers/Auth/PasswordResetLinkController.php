<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Throwable;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate the user input
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 2. We attempt to send the link using Laravel's Password Broker.
        // This generates a secure token, saves it to password_reset_tokens table,
        // and sends the ResetPassword notification email.
        //
        // The try/catch ensures real mailer exceptions (wrong SMTP, timeout, etc.)
        // are logged server-side while the user-facing message stays identical
        // — preserving our anti-enumeration protection.
        try {
            Password::sendResetLink(
                $request->only('email')
            );
        } catch (Throwable $e) {
            // Log the real error for the developer — never expose it to the user.
            Log::error('Password reset email failed to send.', [
                'email'     => $request->input('email'),
                'exception' => $e->getMessage(),
            ]);
        }

        // 3. Always return the same success response regardless of whether the email
        //    exists in the database. This prevents user enumeration attacks where an
        //    attacker could probe which emails are registered by comparing responses.
        return back()->with('status', __('passwords.sent'));
    }
}
