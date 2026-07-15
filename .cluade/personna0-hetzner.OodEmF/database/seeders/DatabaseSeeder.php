<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        // Provision the single owner from env, if configured (production-safe).
        if ($email = env('ADMIN_EMAIL')) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => env('ADMIN_NAME', 'Owner'),
                    'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                ],
            );
        }
    }
}
