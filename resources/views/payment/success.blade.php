@extends('layouts.app')

@section('content')
<div class="auth-card" style="max-width: 480px; text-align: center;">

    {{-- Animated Success Icon --}}
    <div class="success-icon-wrapper">
        <div class="success-circle">
            <svg class="success-checkmark" width="40" height="40" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
    </div>

    <div class="auth-header" style="margin-top: 20px;">
        <h1>Payment Successful!</h1>
        <p>Thank you for your purchase. Your transaction has been confirmed.</p>
    </div>

    {{-- Receipt Card --}}
    <div class="receipt-card">
        <p style="font-size: 11px; text-transform: uppercase; letter-spacing: .1em; color: var(--text-muted); margin-bottom: 14px;">Payment Receipt</p>

        <div class="receipt-row">
            <span class="receipt-label">Product</span>
            <span class="receipt-value">{{ $payment->description }}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Amount</span>
            <span class="receipt-value" style="color: #22c55e; font-weight: 700; font-size: 18px;">{{ $payment->formatted_amount }} {{ $payment->currency }}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Status</span>
            <span class="receipt-value">
                <span class="status-badge status-completed">✓ Completed</span>
            </span>
        </div>
        @if($payment->payer_email)
        <div class="receipt-row">
            <span class="receipt-label">PayPal Account</span>
            <span class="receipt-value" style="font-size: 13px;">{{ $payment->payer_email }}</span>
        </div>
        @endif
        <div class="receipt-row" style="border-bottom: none;">
            <span class="receipt-label">Order ID</span>
            <span class="receipt-value" style="font-size: 11px; font-family: monospace; color: var(--text-muted);">{{ $payment->paypal_order_id }}</span>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div style="display: flex; gap: 12px; margin-top: 24px;">
        <a href="{{ route('payment.history') }}" class="btn-outline" style="flex: 1;">
            View History
        </a>
        <a href="{{ route('dashboard') }}" class="btn-primary" style="flex: 1; text-decoration: none; display: flex; align-items: center; justify-content: center; padding: 14px;">
            Go to Dashboard
        </a>
    </div>
</div>

<style>
    .auth-container { max-width: 500px; }

    /* ── Animated Checkmark ── */
    .success-icon-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 4px;
    }

    .success-circle {
        width: 80px;
        height: 80px;
        background: rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse-success 2s ease-in-out infinite;
    }

    @keyframes pulse-success {
        0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.3); }
        50%       { box-shadow: 0 0 0 12px rgba(34, 197, 94, 0); }
    }

    .checkmark-circle {
        stroke: #22c55e;
        stroke-width: 2;
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark-check {
        stroke: #22c55e;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.4s cubic-bezier(0.65, 0, 0.45, 1) 0.5s forwards;
    }

    @keyframes stroke {
        100% { stroke-dashoffset: 0; }
    }

    /* ── Receipt ── */
    .receipt-card {
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 22px 24px;
        text-align: left;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .receipt-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--text-muted);
    }

    .receipt-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-main);
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-completed {
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.25);
    }

    /* ── Outline Button ── */
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
        background: transparent;
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
</style>
@endsection
