<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Database Seeder — AIVidCatalog18
 *
 * Seeds the database with initial plans and an admin user.
 * Strictly fictional AI-generated content — no illegal/prohibited material.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlansSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
