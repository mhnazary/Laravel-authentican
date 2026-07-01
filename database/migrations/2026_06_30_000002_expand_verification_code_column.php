<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Expand verification_code column from VARCHAR(6) to VARCHAR(64).
     *
     * When we hardened security, we changed OTP storage from storing the
     * 6-digit code in plaintext to storing its SHA-256 hash (64 hex chars).
     * The original column was sized for the 6-digit OTP — this migration
     * widens it to fit the full hash.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // SHA-256 produces a 64-character hex string.
            // We use 64 exactly so there's no wasted space.
            $table->string('verification_code', 64)->nullable()->change();
        });
    }

    /**
     * Reverse the migration — shrink back to 6 characters.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('verification_code', 6)->nullable()->change();
        });
    }
};
