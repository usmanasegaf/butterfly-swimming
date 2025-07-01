<?php
// database/migrations/2025_07_02_000000_create_schedule_murid_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('schedule_murid', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('murid_id'); // id dari tabel users

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->foreign('murid_id')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('schedule_murid');
    }
};