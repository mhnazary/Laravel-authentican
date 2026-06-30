@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1>Create Account</h1>
        <p>Get started with a secure account</p>
    </div>

    <!-- Error Summary (Optional/Backup) -->
    @if ($errors->any())
        <div class="alert alert-danger">
            Please fix the validation errors below.
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name Field -->
        <div class="form-group">
            <label for="name">Full Name</label>
            <div class="input-wrapper">
                <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus autocomplete="name">
            </div>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email">
            </div>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
            </div>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary">
            Sign Up
        </button>
    </form>

    <!-- Navigation Link -->
    <div class="auth-links">
        <span>Already have an account?</span>
        <a href="{{ route('login') }}">Log In</a>
    </div>
</div>
@endsection
