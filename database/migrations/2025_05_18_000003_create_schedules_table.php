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
            $table->date('tanggal')->nullable();
            $table->time('jam')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('guru_id');
            $table->foreign('guru_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
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
