<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTodoRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('todo_id');
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
        Schema::drop('todo_records');
    }
}
