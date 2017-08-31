<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeFileReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_file_receivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id');
            $table->integer('notice_id');
            $table->integer('notice_file_id');
            $table->string('scope_ids');
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
        Schema::drop('notice_file_receivers');
    }
}
