@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1>Reset Password</h1>
        <p>Enter your email and we'll send you a secure link to reset your password.</p>
    </div>

    <!-- Status Alert (Success notification from Laravel broker) -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="email">
            </div>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary" style="margin-top: 10px;">
            Send Password Reset Link
        </button>
    </form>

    <!-- Navigation Link -->
    <div class="auth-links">
        <span>Remember your password?</span>
        <a href="{{ route('login') }}">Log In</a>
    </div>
</div>
@endsection
