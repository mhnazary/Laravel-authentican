<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the 'payments' table to store every PayPal transaction.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // The user who made the payment
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // PayPal's unique order ID (e.g. "5O190127TN364715T")
            // We use this to capture the payment and for reconciliation.
            $table->string('paypal_order_id')->unique();

            // What was purchased
            $table->string('description')->nullable();

            // Financial fields
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');

            // Lifecycle status:
            //   pending   → order created, user not yet approved
            //   completed → payment captured successfully
            //   cancelled → user clicked "Cancel" on PayPal
            //   failed    → capture call returned an error
            $table->enum('status', ['pending', 'completed', 'cancelled', 'failed'])
                  ->default('pending');

            // The buyer's PayPal email — returned after capture
            $table->string('payer_email')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
