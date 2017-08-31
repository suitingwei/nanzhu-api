<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferencePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_plans', function (Blueprint $table) {
            $table->increments('id');;
            $table->string('title');
            $table->integer('movie_id');   //剧组id
            $table->string('file_url');    //文件上传oss之后的url
            $table->string('file_name');   //文件名
            $table->string('file_path');   //文件上传到服务器的路径
            $table->string('creator_id');  //文件上传者user-Id
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
        Schema::drop('reference_plans');
    }
}
