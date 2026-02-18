<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Plans Table — AIVidCatalog18
 *
 * Subscription plans with pricing, duration, and activation status.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                    // e.g., "Basic", "Premium"
            $table->string('slug')->unique();                          // e.g., "basic", "premium"
            $table->decimal('price_usd', 8, 2);                       // e.g., 9.99
            $table->unsignedInteger('duration_days')->default(30);     // Subscription duration
            $table->text('description')->nullable();                   // Plan features description
            $table->boolean('is_active')->default(true);               // Can be purchased?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
