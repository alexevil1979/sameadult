<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Plans Seeder — AIVidCatalog18
 *
 * Creates initial subscription plans for the platform.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class PlansSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(
            ['slug' => 'basic'],
            [
                'name'          => 'Basic',
                'price_usd'     => 9.99,
                'duration_days' => 30,
                'description'   => 'Access to standard AI-generated video catalog. 30-day subscription with HD streaming.',
                'is_active'     => true,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'premium'],
            [
                'name'          => 'Premium',
                'price_usd'     => 19.99,
                'duration_days' => 30,
                'description'   => 'Full access to all AI-generated content including exclusive premium videos. 30-day subscription with 4K streaming and priority support.',
                'is_active'     => true,
            ]
        );
    }
}
