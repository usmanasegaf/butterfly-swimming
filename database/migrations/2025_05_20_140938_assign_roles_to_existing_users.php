<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;

class AssignRolesToExistingUsers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing users
        $users = User::all();

        foreach ($users as $user) {
            // Assign role based on existing 'role' column
            if ($user->role === 'admin') {
                $user->assignRole('admin');
            } else {
                $user->assignRole('user');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all role assignments
        $users = User::all();
        foreach ($users as $user) {
            $user->syncRoles([]);
        }
    }
}