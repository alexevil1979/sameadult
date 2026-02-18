<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Payment Model — AIVidCatalog18
 *
 * Records payment transactions via NOWPayments crypto gateway.
 * Tracks payment status (pending → success/failed) and links
 * to user and plan.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $plan_id
 * @property float $amount
 * @property string $currency
 * @property string $status              pending|success|failed
 * @property string|null $nowpayments_invoice_id
 * @property array|null $metadata         Extra data from payment gateway
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'currency',
        'status',
        'nowpayments_invoice_id',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount'   => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * The user who made this payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The plan this payment is for (nullable for non-subscription payments).
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    // =========================================================================
    // Business Logic
    // =========================================================================

    /**
     * Check if payment was successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if payment is still pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Mark the payment as successful.
     */
    public function markSuccess(): void
    {
        $this->update(['status' => 'success']);
    }

    /**
     * Mark the payment as failed.
     */
    public function markFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
