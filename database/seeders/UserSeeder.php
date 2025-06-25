<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission; // Import the Role model
use Spatie\Permission\Models\Role;

// Import Permission model if you plan to assign permissions too

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
        // --- Admin User ---
        $admin = User::create([
            'name'     => 'Admin Butterfly',
            'email'    => 'admin@butterfly.com',
            'password' => Hash::make('admin.bsc777'),
            'role'     => 'admin', // Keep for backwards compatibility or other app logic
        ]);
                                        // Assign admin role using Spatie
        $admin->assignRole($adminRole); // Assign the role object or name 'admin'

        // Create guru role if it doesn't exist
        $guruRole = Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        $guru     = User::create([
            'name'     => 'Guru Butterfly',
            'email'    => 'guru@butterfly.com',
            'password' => Hash::make('guru123'),
            'role'     => 'guru',
        ]);

        // Assign user role using Spatie
        $guru->assignRole($guruRole);
    }
}
