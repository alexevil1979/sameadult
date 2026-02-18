<?php

namespace App\Providers;

use App\Models\Video;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

/**
 * Auth Service Provider — AIVidCatalog18
 *
 * Registers authorization policies and gates.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Video::class => VideoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate: check if user is admin
        Gate::define('admin', function ($user) {
            return $user->is_admin;
        });

        // Gate: check if user has active subscription
        Gate::define('subscribed', function ($user) {
            return $user->hasActiveSubscription();
        });
    }
}
