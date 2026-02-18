<?php

namespace App\Providers;

use App\Events\SubscriptionActivated;
use App\Listeners\SendSubscriptionConfirmation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Event Service Provider — AIVidCatalog18
 *
 * Registers event-listener mappings.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SubscriptionActivated::class => [
            SendSubscriptionConfirmation::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
