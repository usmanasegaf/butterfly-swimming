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
        $admin = User::create([
            'name' => 'Admin Butterfly',
            'email' => 'admin@butterfly.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', // Keep for backwards compatibility
        ]);
        
        // Assign admin role using Spatie
        $admin->assignRole('admin');

        // Regular User
        $user = User::create([
            'name' => 'User Butterfly',
            'email' => 'user@butterfly.com',
            'password' => Hash::make('user123'),
            'role' => 'user', // Keep for backwards compatibility
        ]);
        
        // Assign user role using Spatie
        $user->assignRole('user');
    }
}