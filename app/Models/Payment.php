<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     * All payment fields are set by our own controller — never by user input directly.
     */
    protected $fillable = [
        'user_id',
        'paypal_order_id',
        'description',
        'amount',
        'currency',
        'status',
        'payer_email',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * The user who made this payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Scopes — convenient query filters
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Only completed (successfully captured) payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Only pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Human-readable status badge with colour context.
     * Usage in Blade: $payment->statusBadge['label'] / ['color']
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'completed' => ['label' => 'Completed', 'color' => '#22c55e'],
            'pending'   => ['label' => 'Pending',   'color' => '#f59e0b'],
            'cancelled' => ['label' => 'Cancelled', 'color' => '#94a3b8'],
            'failed'    => ['label' => 'Failed',    'color' => '#ef4444'],
            default     => ['label' => ucfirst($this->status), 'color' => '#94a3b8'],
        };
    }

    /**
     * Formatted amount with currency symbol.
     * e.g. "$ 9.99"
     */
    public function getFormattedAmountAttribute(): string
    {
        return '$' . ' ' . number_format((float) $this->amount, 2);
    }
}
