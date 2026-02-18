<?php

namespace App\Events;

use App\Models\Subscription;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Subscription Activated — AIVidCatalog18
 *
 * Dispatched when a new subscription is successfully activated
 * after payment confirmation from NOWPayments.
 *
 * Listeners can send welcome emails, unlock content, etc.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class SubscriptionActivated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Subscription $subscription
    ) {}
}
