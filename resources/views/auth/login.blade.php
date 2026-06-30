@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1>Welcome Back</h1>
        <p>Log in to access your secure dashboard</p>
    </div>

    <!-- Error/Success Alerts -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label for="password" style="margin-bottom: 0;">Password</label>
                <a href="{{ route('password.request') }}" style="color: var(--primary); text-decoration: none; font-size: 12px; font-weight: 500; transition: color 0.2s ease;">Forgot Password?</a>
            </div>
            <div class="input-wrapper">
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
            </div>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-top: 10px; margin-bottom: 25px;">
            <input type="checkbox" name="remember" id="remember" style="accent-color: var(--primary); width: 16px; height: 16px; cursor: pointer;">
            <label for="remember" style="margin-bottom: 0; text-transform: none; letter-spacing: normal; font-size: 13px; font-weight: 500; cursor: pointer;">
                Remember this device
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary">
            Log In
        </button>
    </form>

    <!-- Navigation Link -->
    <div class="auth-links">
        <span>Don't have an account?</span>
        <a href="{{ route('register') }}">Sign Up</a>
    </div>
</div>
@endsection
