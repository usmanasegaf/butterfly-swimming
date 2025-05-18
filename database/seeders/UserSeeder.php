<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Butterfly',
            'email' => 'admin@butterfly.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Regular User
        User::create([
            'name' => 'User Butterfly',
            'email' => 'user@butterfly.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
