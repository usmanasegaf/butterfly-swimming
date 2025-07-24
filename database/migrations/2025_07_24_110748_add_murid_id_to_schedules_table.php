<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Menambahkan kolom murid_id untuk relasi 1-to-1 jadwal dengan murid.
            // Kolom ini bisa null karena jadwal bisa dibuat tanpa langsung menugaskan murid.
            $table->foreignId('murid_id')->after('max_students')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['murid_id']);
            $table->dropColumn('murid_id');
        });
    }
};
