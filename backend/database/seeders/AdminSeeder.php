<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = env('SEED_ADMIN_EMAIL', 'admin@example.com');
        $adminUsername = env('SEED_ADMIN_USERNAME', 'admin');
        $adminPassword = env('SEED_ADMIN_PASSWORD', 'password');

        $existing = User::where('email', $adminEmail)->first();
        if ($existing) {
            $existing->update(['type_of_user' => 'admin']);
            return;
        }

        User::create([
            'username' => $adminUsername,
            'email' => $adminEmail,
            'full_name' => 'Administrator',
            'password' => bcrypt($adminPassword),
            'type_of_user' => 'admin',
        ]);
    }
}
