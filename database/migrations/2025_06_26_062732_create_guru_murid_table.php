<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('guru_murid', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('murid_id');
            $table->timestamps();

            $table->foreign('guru_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('murid_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('guru_murid');
    }

};
