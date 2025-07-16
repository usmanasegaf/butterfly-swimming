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
        Schema::table('registrations', function (Blueprint $table) {
            // Tambahkan kolom guru_id sebagai foreign key
            // Pastikan kolom ini bisa null jika pendaftaran belum ditugaskan ke guru
            $table->foreignId('guru_id')
                  ->nullable()
                  ->constrained('users') // Asumsi guru adalah entitas User
                  ->onDelete('set null') // Jika guru dihapus, set ID guru di pendaftaran menjadi null
                  ->after('swimming_course_id'); // Letakkan setelah kolom swimming_course_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('guru_id'); // Hapus foreign key constraint terlebih dahulu
            $table->dropColumn('guru_id'); // Kemudian hapus kolomnya
        });
    }
};
