<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Payments Table — AIVidCatalog18
 *
 * Records all payment transactions via NOWPayments crypto gateway.
 * Tracks status lifecycle: pending → success/failed.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('usd');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('nowpayments_invoice_id')->nullable()->unique();
            $table->json('metadata')->nullable()->comment('Extra gateway response data');
            $table->timestamps();

            // Index for webhook lookups
            $table->index('nowpayments_invoice_id');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
