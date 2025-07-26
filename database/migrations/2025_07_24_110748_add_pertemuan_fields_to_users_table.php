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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan total pertemuan yang dibeli murid.
            $table->integer('jumlah_pertemuan_paket')->after('course_assigned_at')->nullable();
            // Menambahkan kolom untuk melacak pertemuan saat ini.
            $table->integer('pertemuan_ke')->after('jumlah_pertemuan_paket')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('jumlah_pertemuan_paket');
            $table->dropColumn('pertemuan_ke');
        });
    }
};

