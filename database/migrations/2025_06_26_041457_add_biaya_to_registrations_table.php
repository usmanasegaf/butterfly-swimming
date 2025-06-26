<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('registrations', function ($table) {
            $table->integer('biaya')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('registrations', function ($table) {
            $table->dropColumn('biaya');
        });
    }
};
