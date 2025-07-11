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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // Asumsi murid adalah entitas User
            $table->date('attendance_date'); // Tanggal absensi diambil
            $table->string('status', 20); // Contoh: 'hadir', 'alpha'
            $table->timestamp('attended_at')->nullable(); // Waktu absensi dicatat

            // --- KOLOM BARU UNTUK LOKASI GURU ---
            $table->double('teacher_latitude')->nullable();  // Latitude lokasi guru saat absen
            $table->double('teacher_longitude')->nullable(); // Longitude lokasi guru saat absen
            $table->double('distance_from_course')->nullable(); // Jarak guru dari lokasi kursus (dalam meter)

            $table->timestamps();

            // Memastikan hanya ada satu record absensi per murid, per jadwal, per hari
            $table->unique(['schedule_id', 'student_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};