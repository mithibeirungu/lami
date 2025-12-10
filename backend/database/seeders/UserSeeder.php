<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create first admin (overseer)
        User::create([
            'username' => 'overseer',
            'email' => 'overseer@lami.local',
            'full_name' => 'Head Overseer',
            'password_hash' => Hash::make('password'),
            'role' => 'overseer',
        ]);

        // Create secondary admin (motor_scribe)
        User::create([
            'username' => 'scribe',
            'email' => 'scribe@lami.local',
            'full_name' => 'Motor Scribe',
            'password_hash' => Hash::make('password'),
            'role' => 'motor_scribe',
        ]);

        // Create regular users (drivers)
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'username' => "driver{$i}",
                'email' => "driver{$i}@lami.local",
                'full_name' => "Driver User {$i}",
                'password_hash' => Hash::make('password'),
                'role' => 'driver',
            ]);
        }
    }
}
