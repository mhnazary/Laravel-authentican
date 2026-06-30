@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1>New Password</h1>
        <p>Set a secure new password for your account.</p>
    </div>

    <!-- Error Summary -->
    @if ($errors->any())
        <div class="alert alert-danger">
            Please fix the validation errors below.
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token (hidden) -->
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $request->email) }}" required autocomplete="email">
            </div>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">New Password</label>
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
        <button type="submit" class="btn-primary" style="margin-top: 10px;">
            Reset Password
        </button>
    </form>
</div>
@endsection
