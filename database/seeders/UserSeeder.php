<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds test beta user for development/testing purposes.
     * In production, create users manually via artisan tinker or similar.
     */
    public function run(): void
    {
        // Create test beta user
        User::create([
            'name' => 'Beta Tester',
            'email' => 'beta@codeforgesystems.com',
            'password' => Hash::make('BetaTest2025!'),
            'email_verified_at' => now(),
        ]);

        // You can add more users here manually as needed
        // Example:
        // User::create([
        //     'name' => 'Your Name',
        //     'email' => 'your.email@example.com',
        //     'password' => Hash::make('your-password-here'),
        //     'email_verified_at' => now(),
        // ]);
    }
}
