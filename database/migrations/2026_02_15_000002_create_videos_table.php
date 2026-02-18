<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Videos Table — AIVidCatalog18
 *
 * AI-generated video catalog entries with multilingual metadata.
 * Files stored on private disk; thumbnails on public disk.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 * All content is synthetic. No real persons depicted.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->json('title');                                     // {"en": "...", "ru": "...", "es": "..."}
            $table->json('description')->nullable();                   // {"en": "...", "ru": "...", "es": "..."}
            $table->json('tags')->nullable();                          // ["tag1", "tag2", ...]
            $table->string('category')->default('general');            // fantasy, anime, roleplay, etc.
            $table->unsignedInteger('duration_seconds')->default(0);   // Video length
            $table->string('file_path');                               // Path on 'videos' disk
            $table->string('thumbnail_path')->nullable();              // Path on 'thumbnails' disk
            $table->boolean('is_premium')->default(false);             // Requires subscription?
            $table->unsignedInteger('views_count')->default(0);        // View counter
            $table->timestamp('approved_at')->nullable();              // Admin approval timestamp
            $table->timestamps();

            // Indexes for common queries
            $table->index('category');
            $table->index('is_premium');
            $table->index('approved_at');
            $table->index('views_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
