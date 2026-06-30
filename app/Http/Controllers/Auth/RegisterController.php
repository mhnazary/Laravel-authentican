<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // 1. Create and persist the user to the database
        // The password will be automatically hashed by Eloquent due to casts() on the User model.
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        // 2. Authenticate the newly registered user
        Auth::login($user);

        // 3. Regenerate the session ID to prevent session fixation attacks.
        //    This mirrors what LoginController does after a successful login.
        $request->session()->regenerate();

        // 4. Redirect to the dashboard
        return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome to your dashboard.');
    }
}
