@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h1>Verify Email</h1>
        <p>Enter the 6-digit code sent to your email address.</p>
    </div>

    <!-- Alerts -->
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            A new verification code has been sent to your email.
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- 6-Digit Code Verification Form -->
    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf

        <div class="form-group">
            <label for="code">Verification Code</label>
            <div class="input-wrapper">
                <input id="code" type="text" name="code" class="form-control @error('code') is-invalid @enderror" placeholder="123456" required autofocus autocomplete="one-time-code" maxlength="6" style="text-align: center; font-size: 24px; letter-spacing: 8px; font-weight: 700;">
            </div>
            @error('code')
                <span class="error-message" style="text-align: center;">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary" style="margin-bottom: 15px;">
            Verify Account
        </button>
    </form>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; font-size: 13px;">
        <!-- Resend email form -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" style="background: none; border: none; color: var(--primary); font-weight: 500; cursor: pointer; text-decoration: underline; padding: 0; font-family: inherit;">
                Resend Code
            </button>
        </form>

        <!-- Log Out Form -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background: none; border: none; color: var(--text-muted); font-weight: 500; cursor: pointer; text-decoration: underline; padding: 0; font-family: inherit;">
                Log Out
            </button>
        </form>
    </div>
</div>
@endsection
