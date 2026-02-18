<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Plan Model — AIVidCatalog18
 *
 * Represents a subscription plan (e.g., Basic $9.99/30 days, Premium $19.99/30 days).
 * Plans define pricing, duration, and access level for the platform.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property float $price_usd
 * @property int $duration_days
 * @property string $description
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price_usd',
        'duration_days',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_usd'     => 'decimal:2',
            'duration_days' => 'integer',
            'is_active'     => 'boolean',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * Get all subscriptions for this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all payments associated with this plan.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Scope to only active plans available for purchase.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * Format the price for display (e.g., "$9.99").
     */
    public function formattedPrice(): string
    {
        return '$' . number_format($this->price_usd, 2);
    }
}
