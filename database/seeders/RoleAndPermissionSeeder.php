<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Swimming course permissions
            'view swimming courses',
            'create swimming course',
            'edit swimming course',
            'delete swimming course',

            // Registration permissions
            'view registrations',
            'approve registration',
            'reject registration',
            'delete registration',

            // User registrations permissions
            'register to course',
            'view own registrations',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $guruRole = Role::create(['name' => 'guru']);
        $guruRole->givePermissionTo([
            'view swimming courses',
            'register to course',
            'view own registrations',
        ]);
    }
}
