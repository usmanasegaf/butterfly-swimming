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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swimming_course_id')->constrained()->onDelete('cascade');
            // Kolom lama dihapus: tanggal, jam, location, is_active
            // Tambahkan kolom baru untuk jadwal berulang (hari dan jam)
            $table->unsignedBigInteger('guru_id');
            $table->foreign('guru_id')->references('id')->on('users')->onDelete('cascade');

            // Kolom baru untuk lokasi (foreign key)
            $table->foreignId('location_id')->constrained()->onDelete('cascade');

            // Kolom baru untuk jadwal berulang
            $table->integer('day_of_week')->comment('1=Senin, 2=Selasa, ..., 7=Minggu');
            $table->time('start_time_of_day');
            $table->time('end_time_of_day');

            // Kolom untuk maksimal murid
            $table->integer('max_students')->default(0); // Atau sesuai default yang diinginkan

            // Ganti is_active menjadi status
            $table->string('status')->default('active'); // Misalnya 'active', 'cancelled', 'completed'

            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};