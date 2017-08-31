<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCooperateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperate_invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');         //公司,剧本
            $table->integer('applier_id');  //发出邀约的人
            $table->integer('receiver_id'); //接收到邀约的公司或者剧本
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
        Schema::drop('cooperate_invitations');
    }
}
