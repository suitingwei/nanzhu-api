<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');         //发布者
            $table->integer('movie_id');       //发布的剧组
            $table->string('group');           //组别
            $table->timestamp('date');         //选择的日期 YYYY-mm-dd
            $table->timestamp('depart_time');  //出发时间
            $table->timestamp('arrive_time');  //到达时间
            $table->timestamp('action_time');  //开拍时间
            $table->timestamp('finish_time');  //收工时间
            $table->text('note');
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
        Schema::drop('daily_reports');
    }
}
