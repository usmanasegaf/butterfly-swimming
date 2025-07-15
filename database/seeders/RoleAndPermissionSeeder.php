<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            'view swimming courses',
            'create swimming course',
            'edit swimming course',
            'delete swimming course',

            'view registrations',
            'approve registration',
            'reject registration',
            'delete registration',

            'register to course',
            'view own registrations',

            'view schedules',
            'create schedule',
            'edit schedule',
            'delete schedule',

            'view all attendances',
            'generate all attendance reports',

            'view active students',
            'manage active students',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            // Ganti Permission::create() dengan Permission::firstOrCreate()
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign all permissions to the 'admin' role
        // Ganti Role::create() dengan Role::firstOrCreate()
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all()); // Admin gets all permissions

        // Assign specific permissions to the 'guru' role
        // Ganti Role::create() dengan Role::firstOrCreate()
        $guruRole = Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        $guruRole->givePermissionTo([
            'view swimming courses',
            'register to course',
            'view own registrations',
        ]);

        // Assign specific permissions to the 'murid' role
        // Ganti Role::create() dengan Role::firstOrCreate()
        $muridRole = Role::firstOrCreate(['name' => 'murid', 'guard_name' => 'web']);
        $muridRole->givePermissionTo([
            'view swimming courses',
        ]);
    }
}
