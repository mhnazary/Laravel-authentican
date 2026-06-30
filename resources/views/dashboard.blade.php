@extends('layouts.app')

@section('content')
<div class="auth-card" style="max-width: 500px;">
    <div class="auth-header">
        <h1>Dashboard</h1>
        <p>You are securely logged in!</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 30px; background: rgba(15, 23, 42, 0.4); padding: 20px; border-radius: 10px; border: 1px solid var(--border-color);">
        <p style="margin-bottom: 10px; font-size: 14px; color: var(--text-muted);">User Profile Details:</p>
        <h3 style="margin-bottom: 5px;">{{ Auth::user()->name }}</h3>
        <p style="color: var(--text-muted); font-size: 14px;">{{ Auth::user()->email }}</p>
    </div>

    <!-- Logout form -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #ef4444, #f43f5e); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">
            Log Out
        </button>
    </form>
</div>
@endsection
