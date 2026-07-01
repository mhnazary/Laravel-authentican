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

    <!-- User Profile Card -->
    <div style="margin-bottom: 24px; background: rgba(15, 23, 42, 0.4); padding: 20px; border-radius: 10px; border: 1px solid var(--border-color);">
        <p style="margin-bottom: 10px; font-size: 14px; color: var(--text-muted);">User Profile Details:</p>
        <h3 style="margin-bottom: 5px;">{{ Auth::user()->name }}</h3>
        <p style="color: var(--text-muted); font-size: 14px;">{{ Auth::user()->email }}</p>
    </div>

    <!-- Payment Actions -->
    <div style="margin-bottom: 24px;">
        <p style="font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: var(--text-muted); margin-bottom: 12px;">Payments</p>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('payment.checkout') }}"
               style="flex: 1; background: linear-gradient(135deg, #003087, #009cde); border: none; border-radius: 10px; padding: 13px 16px; color: white; font-size: 14px; font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .25s ease; box-shadow: 0 4px 12px rgba(0,48,135,.3);"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,156,222,.4)'"
               onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 12px rgba(0,48,135,.3)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white" opacity=".9"><path d="M7 15h2c3.314 0 6-2.686 6-6H9a6 6 0 01-6 6v2a8 8 0 008 8h2v-2a6 6 0 006-6h-2a4 4 0 01-4 4H9a4 4 0 01-4-4v-2z" opacity=".5"/><path d="M19 3H9C6.239 3 4 5.239 4 8v1h11a6 6 0 016 6v1h1a2 2 0 002-2V5a2 2 0 00-2-2z"/></svg>
                Pay with PayPal
            </a>
            <a href="{{ route('payment.history') }}"
               style="flex: 1; background: rgba(15,23,42,.5); border: 1px solid var(--border-color); border-radius: 10px; padding: 13px 16px; color: var(--text-muted); font-size: 14px; font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .25s ease;"
               onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
               onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-muted)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 10h18" stroke="currentColor" stroke-width="1.8"/></svg>
                History
            </a>
        </div>
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
