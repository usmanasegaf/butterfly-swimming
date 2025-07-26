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
        Schema::table('swimming_courses', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan jumlah pertemuan per paket kursus.
            $table->integer('jumlah_pertemuan')->after('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('swimming_courses', function (Blueprint $table) {
            $table->dropColumn('jumlah_pertemuan');
        });
    }
};
