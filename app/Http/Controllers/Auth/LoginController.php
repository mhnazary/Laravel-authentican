<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
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
        // 1. Validate the form and authenticate credentials (with rate-limiting)
        $request->authenticate();

        // 2. Regenerate the session ID to mitigate session fixation attacks
        $request->session()->regenerate();

        // 3. Redirect the user to their intended destination (or fallback to the dashboard)
        return redirect()->intended(route('dashboard'))
            ->with('success', 'Logged in successfully! Welcome back.');
    }

    /**
     * Destroy an authenticated session (Log Out).
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Log the user out of the web guard
        Auth::guard('web')->logout();

        // 2. Invalidate the user session
        $request->session()->invalidate();

        // 3. Regenerate the CSRF token to prevent CSRF vulnerabilities
        $request->session()->regenerateToken();

        // 4. Redirect to the home page or login screen with a success message
        return redirect('/login')->with('success', 'You have been successfully logged out.');
    }
}
