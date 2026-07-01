<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
        'verification_code_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the email verification notification.
     * Overridden to generate a secure 6-digit code instead of a signed URL.
     */
    public function sendEmailVerificationNotification(): void
    {
        // 1. Generate a cryptographically secure 6-digit integer code
        $code = (string) random_int(100000, 999999);

        // 2. Persist a SHA-256 hash of the code (never store OTPs in plaintext).
        //    The raw $code is only sent via email; the DB holds a hash.
        $this->forceFill([
            'verification_code'            => hash('sha256', $code),
            'verification_code_expires_at' => now()->addMinutes(15),
        ])->save();

        // 3. Dispatch the email notification
        $this->notify(new \App\Notifications\SendEmailVerificationCode($code));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * All payments made by this user.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
