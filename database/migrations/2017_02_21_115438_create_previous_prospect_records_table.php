<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreviousProspectRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previous_prospect_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('previous_prospect_id');
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
        Schema::drop('previous_prospect_records');
    }
}
