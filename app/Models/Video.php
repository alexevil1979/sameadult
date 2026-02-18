<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Video Model — AIVidCatalog18
 *
 * Represents an AI-generated video in the catalog.
 * Multilingual title/description stored as JSON.
 * Videos are stored on the private 'videos' disk and served via signed URLs.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 * All content is synthetic/AI-generated. No real persons depicted.
 *
 * @property int $id
 * @property array $title           JSON: {"en": "...", "ru": "...", "es": "..."}
 * @property array $description     JSON: {"en": "...", "ru": "...", "es": "..."}
 * @property array $tags            JSON: ["tag1", "tag2"]
 * @property string $category       e.g., "fantasy", "anime", "roleplay"
 * @property int $duration_seconds
 * @property string $file_path      Relative path on 'videos' disk
 * @property string $thumbnail_path Relative path on 'thumbnails' disk
 * @property bool $is_premium
 * @property int $views_count
 * @property \Carbon\Carbon|null $approved_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'tags',
        'category',
        'duration_seconds',
        'file_path',
        'thumbnail_path',
        'is_premium',
        'views_count',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'title'            => 'array',
            'description'      => 'array',
            'tags'             => 'array',
            'duration_seconds' => 'integer',
            'is_premium'       => 'boolean',
            'views_count'      => 'integer',
            'approved_at'      => 'datetime',
        ];
    }

    // =========================================================================
    // Accessors
    // =========================================================================

    /**
     * Get the localized title based on current app locale.
     */
    public function localizedTitle(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $titles = $this->title ?? [];

        return $titles[$locale] ?? $titles['en'] ?? 'Untitled';
    }

    /**
     * Get the localized description based on current app locale.
     */
    public function localizedDescription(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $descriptions = $this->description ?? [];

        return $descriptions[$locale] ?? $descriptions['en'] ?? '';
    }

    /**
     * Get the full thumbnail URL (public).
     */
    public function thumbnailUrl(): string
    {
        if ($this->thumbnail_path) {
            return Storage::disk('thumbnails')->url($this->thumbnail_path);
        }

        // Default placeholder
        return asset('images/video-placeholder.jpg');
    }

    /**
     * Get a temporary signed URL for the video file (60 min expiry).
     * Only for authenticated & authorized users.
     */
    public function signedVideoUrl(int $minutes = 60): string
    {
        // Since local driver doesn't support temporaryUrl natively,
        // we use a route-based signed URL approach
        return route('videos.stream', [
            'video' => $this->id,
            'signature' => hash_hmac('sha256', $this->id . '|' . $this->file_path, config('app.key')),
            'expires' => now()->addMinutes($minutes)->timestamp,
        ]);
    }

    /**
     * Format duration as mm:ss.
     */
    public function formattedDuration(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    // =========================================================================
    // Business Logic
    // =========================================================================

    /**
     * Check if a given user can access this video.
     *
     * Free videos are accessible to all visitors.
     * Premium videos require an active subscription.
     */
    public function isAccessibleBy(?User $user): bool
    {
        // Free content is accessible to everyone
        if (!$this->is_premium) {
            return true;
        }

        // Premium content requires an active subscription
        if ($user && $user->hasActiveSubscription()) {
            return true;
        }

        return false;
    }

    /**
     * Check if the video has been approved by admin.
     */
    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    /**
     * Increment the view counter.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Only approved videos visible in the public catalog.
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    /**
     * Only premium videos.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Only free videos.
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Filter by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
