<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class, // Run this first to create roles and permissions
            UserSeeder::class,
            SwimmingCourseSeeder::class,
            LocationSeeder::class,
            ScheduleSeeder::class,
            ScheduleMuridSeeder::class,
        ]);
    }
}