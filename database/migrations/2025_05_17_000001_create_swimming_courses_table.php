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
        Schema::create('swimming_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('level');
            $table->text('description');
            $table->integer('price');
            $table->integer('duration');
            $table->integer('sessions_per_week');
            $table->integer('max_participants')->nullable();
            $table->string('instructor')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swimming_courses');
    }
};
