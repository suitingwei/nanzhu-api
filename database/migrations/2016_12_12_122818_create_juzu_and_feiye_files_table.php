<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJuzuAndFeiyeFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id'); // 关联的剧组通知,剧本扉页id
            $table->string('type');      //剧组通知,剧本扉页
            $table->string('file_name'); //上传的文件名字
            $table->string('file_type'); //文件类型,xlsx,doc,pdf
            $table->string('file_url');  //文件的url
            $table->string('file_path'); //文件路径
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
        Schema::drop('message_files');
    }
}
