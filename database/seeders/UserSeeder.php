<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Import the Role model
use Spatie\Permission\Models\Permission; // Import Permission model if you plan to assign permissions too

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Create Roles ---
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        // Create user role if it doesn't exist
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // --- Admin User ---
        $admin = User::create([
            'name' => 'Admin Butterfly',
            'email' => 'admin@butterfly.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', // Keep for backwards compatibility or other app logic
        ]);
        
        // Assign admin role using Spatie
        $admin->assignRole($adminRole); // Assign the role object or name 'admin'

        // --- Regular User ---
        $user = User::create([
            'name' => 'User Butterfly',
            'email' => 'user@butterfly.com',
            'password' => Hash::make('user123'),
            'role' => 'user', // Keep for backwards compatibility or other app logic
        ]);
        
        // Assign user role using Spatie
        $user->assignRole($userRole); 
    }
}