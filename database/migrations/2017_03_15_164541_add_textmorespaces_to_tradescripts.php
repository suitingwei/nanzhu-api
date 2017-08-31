<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTextmorespacesToTradescripts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_scripts', function (Blueprint $table) {
            //
            DB::unprepared('ALTER TABLE trade_scripts MODIFY COLUMN content MEDIUMTEXT');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_scripts', function (Blueprint $table) {
            //
        });
    }
}
