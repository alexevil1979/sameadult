<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Admin User Seeder — AIVidCatalog18
 *
 * Creates a default admin user for initial platform setup.
 * CHANGE THE PASSWORD IN PRODUCTION!
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@aividcatalog18.com'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('admin12345'),
                'is_admin'          => true,
                'language'          => 'en',
                'email_verified_at' => now(),
            ]
        );
    }
}
