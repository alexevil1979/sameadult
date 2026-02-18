<?php

namespace App\Listeners;

use App\Events\SubscriptionActivated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Listener: Send Subscription Confirmation — AIVidCatalog18
 *
 * Sends a confirmation email/notification to the user when
 * their subscription is activated.
 *
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class SendSubscriptionConfirmation
{
    public function handle(SubscriptionActivated $event): void
    {
        $subscription = $event->subscription;
        $user = $subscription->user;
        $plan = $subscription->plan;

        Log::info('Sending subscription confirmation', [
            'user_id' => $user->id,
            'plan'    => $plan->name,
            'ends_at' => $subscription->ends_at->toDateTimeString(),
        ]);

        // In production, send a proper Mailable:
        // Mail::to($user)->send(new \App\Mail\SubscriptionConfirmed($subscription));

        // For now, just log it (MAIL_MAILER=log in .env)
    }
}
