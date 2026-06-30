<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', [
            'request' => $request,
            'token' => $request->route('token')
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate the form inputs securely.
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            // Enforce password strength: minimum 8 chars, mixed case, letters, numbers.
            // max:72 matches bcrypt's effective byte limit — prevents long-password DoS attacks.
            'password' => ['required', 'string', 'max:72', 'confirmed', Rules\Password::min(8)->letters()->numbers()->mixedCase()->uncompromised()],
        ]);

        // 2. Execute the password reset via Laravel's Password Broker.
        // It validates the email, token validity, and expiration.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                // The password gets automatically hashed due to the 'password' => 'hashed'
                // cast on the User model. We also rotate the remember_token for security.
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                // Fire the password reset event so Laravel knows the password changed.
                event(new PasswordReset($user));
            }
        );

        // 3. If successful, redirect to login page.
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', __($status));
        }

        // If validation by the broker failed, throw a validation error.
        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
