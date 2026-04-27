<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // public user
        User::updateOrCreate(
            ['email' => 'public@test.com'],
            [
                'name' => 'Public User',
                'password' => Hash::make('123456'),
                'role' => 'public',
            ]
        );

        // staff user
        User::updateOrCreate(
            ['email' => 'staff@test.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('123456'),
                'role' => 'staff',
            ]
        );
    }
}
