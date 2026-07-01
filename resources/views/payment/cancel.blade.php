@extends('layouts.app')

@section('content')
<div class="auth-card" style="max-width: 440px; text-align: center;">

    {{-- Cancel Icon --}}
    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <div class="cancel-circle">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                <path d="M18 6L6 18M6 6l12 12" stroke="#94a3b8" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <div class="auth-header" style="margin-top: 0;">
        <h1 style="background: linear-gradient(135deg, #94a3b8, #64748b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Payment Cancelled
        </h1>
        <p>No worries — your payment was cancelled and you have not been charged.</p>
    </div>

    <div class="info-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="flex-shrink:0">
            <circle cx="12" cy="12" r="10" stroke="#6366f1" stroke-width="2"/>
            <path d="M12 8v4M12 16h.01" stroke="#6366f1" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <p>You can retry your purchase any time from the checkout page.</p>
    </div>

    <div style="display: flex; gap: 12px; margin-top: 28px;">
        <a href="{{ route('payment.checkout') }}" class="btn-primary" style="flex: 1; text-decoration: none; display: flex; align-items: center; justify-content: center; padding: 14px;">
            Try Again
        </a>
        <a href="{{ route('dashboard') }}" class="btn-outline" style="flex: 1;">
            Dashboard
        </a>
    </div>
</div>

<style>
    .cancel-circle {
        width: 72px;
        height: 72px;
        background: rgba(148, 163, 184, 0.08);
        border: 2px solid rgba(148, 163, 184, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-box {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: rgba(99, 102, 241, 0.08);
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 12px;
        padding: 14px 16px;
        text-align: left;
        margin-top: 8px;
    }

    .info-box p {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.5;
    }

    .btn-outline {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-muted);
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
</style>
@endsection
