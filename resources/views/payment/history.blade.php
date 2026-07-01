@extends('layouts.app')

@section('content')
<div class="auth-card history-card">
    <div class="auth-header">
        <h1>Payment History</h1>
        <p>All transactions linked to your account</p>
    </div>

    {{-- Summary Strip --}}
    @php
        $completed = $payments->where('status', 'completed');
        $totalSpent = $completed->sum('amount');
    @endphp
    <div class="summary-strip">
        <div class="summary-item">
            <span class="summary-number">{{ $payments->total() }}</span>
            <span class="summary-label">Transactions</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <span class="summary-number" style="color: #22c55e;">${{ number_format($totalSpent, 2) }}</span>
            <span class="summary-label">Total Spent</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <span class="summary-number">{{ $completed->count() }}</span>
            <span class="summary-label">Completed</span>
        </div>
    </div>

    {{-- Payments List --}}
    @if($payments->isEmpty())
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" style="margin-bottom: 12px; opacity: .3;">
                <rect x="2" y="5" width="20" height="14" rx="3" stroke="#94a3b8" stroke-width="1.5"/>
                <path d="M2 10h20" stroke="#94a3b8" stroke-width="1.5"/>
            </svg>
            <p>No payments yet.</p>
            <a href="{{ route('payment.checkout') }}" class="btn-primary" style="margin-top: 16px; display: inline-block; text-decoration: none; padding: 12px 24px; width: auto;">
                Make Your First Payment
            </a>
        </div>
    @else
        <div class="payment-list">
            @foreach($payments as $payment)
            <div class="payment-row">
                {{-- Icon --}}
                <div class="payment-icon" style="background: {{ $payment->status === 'completed' ? 'rgba(34,197,94,.12)' : ($payment->status === 'pending' ? 'rgba(245,158,11,.12)' : 'rgba(148,163,184,.08)') }};">
                    @if($payment->status === 'completed')
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @elseif($payment->status === 'pending')
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="#f59e0b" stroke-width="2"/><path d="M12 7v5l3 3" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/></svg>
                    @else
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/></svg>
                    @endif
                </div>

                {{-- Details --}}
                <div class="payment-details">
                    <span class="payment-desc">{{ $payment->description ?? 'Payment' }}</span>
                    <span class="payment-meta">
                        {{ $payment->created_at->format('M d, Y · g:i A') }}
                        @if($payment->payer_email)
                            · {{ $payment->payer_email }}
                        @endif
                    </span>
                </div>

                {{-- Right side --}}
                <div class="payment-right">
                    <span class="payment-amount">{{ $payment->formatted_amount }}</span>
                    <span class="status-pill" style="background: {{ $payment->statusBadge['color'] }}1a; color: {{ $payment->statusBadge['color'] }}; border-color: {{ $payment->statusBadge['color'] }}40;">
                        {{ $payment->statusBadge['label'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($payments->hasPages())
        <div style="margin-top: 20px;">
            {{ $payments->links() }}
        </div>
        @endif
    @endif

    {{-- Actions --}}
    <div class="auth-links" style="margin-top: 24px;">
        <a href="{{ route('payment.checkout') }}">+ New Payment</a>
        <span style="margin: 0 10px; color: var(--border-color);">|</span>
        <a href="{{ route('dashboard') }}">← Dashboard</a>
    </div>
</div>

<style>
    .auth-container { max-width: 640px; }
    .history-card   { max-width: 600px; padding: 36px; }

    /* ── Summary Strip ── */
    .summary-strip {
        display: flex;
        align-items: center;
        justify-content: space-around;
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .summary-number {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
    }

    .summary-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--text-muted);
    }

    .summary-divider {
        width: 1px;
        height: 36px;
        background: var(--border-color);
    }

    /* ── Payment Rows ── */
    .payment-list {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .payment-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 10px;
        transition: background 0.15s ease;
    }

    .payment-row:hover {
        background: rgba(255,255,255,0.03);
    }

    .payment-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .payment-details {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .payment-desc {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-main);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .payment-meta {
        font-size: 12px;
        color: var(--text-muted);
    }

    .payment-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
        flex-shrink: 0;
    }

    .payment-amount {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
    }

    .status-pill {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
        border: 1px solid;
    }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
</style>
@endsection
