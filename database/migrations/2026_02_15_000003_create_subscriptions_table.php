<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Subscriptions Table — AIVidCatalog18
 *
 * Tracks user subscription periods and statuses.
 * Created upon successful payment confirmation from NOWPayments.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->enum('status', ['active', 'expired', 'canceled'])->default('active');
            $table->string('payment_id')->nullable()->comment('Reference to NOWPayments payment ID');
            $table->timestamps();

            // Indexes for subscription lookups
            $table->index(['user_id', 'status', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
