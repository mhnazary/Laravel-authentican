@extends('layouts.app')

@section('content')
<div class="auth-card" style="max-width: 480px;">
    <div class="auth-header">
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #003087, #009cde); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                <path d="M7 15h2c3.314 0 6-2.686 6-6H9a6 6 0 01-6 6v2a8 8 0 008 8h2v-2a6 6 0 006-6h-2a4 4 0 01-4 4H9a4 4 0 01-4-4v-2z" fill="white" opacity=".5"/>
                <path d="M19 3H9C6.239 3 4 5.239 4 8v1h11a6 6 0 016 6v1h1a2 2 0 002-2V5a2 2 0 00-2-2z" fill="white"/>
            </svg>
        </div>
        <h1>Checkout</h1>
        <p>Complete your purchase securely with PayPal</p>
    </div>

    {{-- Error Alert --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Product Card --}}
    <div class="product-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="font-size: 12px; text-transform: uppercase; letter-spacing: .08em; color: var(--text-muted); margin-bottom: 4px;">Product</p>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">{{ $product }}</h3>
            </div>
            <div class="price-tag">
                <span style="font-size: 13px; font-weight: 500; color: var(--text-muted);">{{ $currency }}</span>
                <span style="font-size: 28px; font-weight: 800; color: var(--text-main);">${{ $amount }}</span>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid var(--border-color); margin: 18px 0;">

        <ul class="feature-list">
            <li>
                <span class="check-icon">✓</span>
                Instant access upon payment
            </li>
            <li>
                <span class="check-icon">✓</span>
                Secure, encrypted transaction
            </li>
            <li>
                <span class="check-icon">✓</span>
                Powered by PayPal — no card info stored here
            </li>
        </ul>
    </div>

    {{-- PayPal Button --}}
    <form method="POST" action="{{ route('payment.create') }}" style="margin-top: 24px;">
        @csrf
        <button type="submit" class="btn-paypal" id="paypal-checkout-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="margin-right: 10px; flex-shrink: 0;">
                <path d="M7 15h2c3.314 0 6-2.686 6-6H9a6 6 0 01-6 6v2a8 8 0 008 8h2v-2a6 6 0 006-6h-2a4 4 0 01-4 4H9a4 4 0 01-4-4v-2z" fill="white" opacity=".7"/>
                <path d="M19 3H9C6.239 3 4 5.239 4 8v1h11a6 6 0 016 6v1h1a2 2 0 002-2V5a2 2 0 00-2-2z" fill="white"/>
            </svg>
            Pay with PayPal — ${{ $amount }} {{ $currency }}
        </button>
    </form>

    <div class="auth-links" style="margin-top: 20px;">
        <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
        <span style="margin: 0 10px; color: var(--border-color);">|</span>
        <a href="{{ route('payment.history') }}">View Payment History</a>
    </div>
</div>

<style>
    .auth-container { max-width: 500px; }

    .product-card {
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 22px 24px;
        margin-top: 8px;
    }

    .price-tag {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        line-height: 1.1;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .feature-list li {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: var(--text-muted);
        gap: 10px;
    }

    .check-icon {
        width: 20px;
        height: 20px;
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .btn-paypal {
        width: 100%;
        background: linear-gradient(135deg, #003087, #009cde);
        border: none;
        border-radius: 12px;
        padding: 16px 20px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 48, 135, 0.4);
        letter-spacing: 0.01em;
    }

    .btn-paypal:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 156, 222, 0.5);
        background: linear-gradient(135deg, #002574, #0085c0);
    }

    .btn-paypal:active {
        transform: translateY(0);
    }
</style>
@endsection
