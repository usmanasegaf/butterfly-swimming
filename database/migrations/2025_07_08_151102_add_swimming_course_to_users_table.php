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
            // Kolom untuk ID kursus renang yang ditetapkan
            // Ini akan menjadi foreign key ke tabel swimming_courses
            $table->foreignId('swimming_course_id')
                  ->nullable()
                  ->constrained('swimming_courses') // Menambahkan foreign key ke tabel swimming_courses
                  ->onDelete('set null'); // Jika kursus dihapus, set ID kursus di user menjadi null

            // Kolom untuk timestamp kapan kursus ditetapkan
            $table->timestamp('course_assigned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key sebelum menghapus kolom
            $table->dropConstrainedForeignId('swimming_course_id');

            // Hapus kolom
            $table->dropColumn('swimming_course_id');
            $table->dropColumn('course_assigned_at');
        });
    }
};