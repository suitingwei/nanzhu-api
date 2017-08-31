<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyReportRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_report_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('daily_report_id');
            $table->integer('movie_id');
            $table->string('user_name');
            $table->string('movie_name');
            $table->string('group_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('daily_report_records');
    }
}
