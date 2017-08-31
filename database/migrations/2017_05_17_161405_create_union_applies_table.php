<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnionAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('union_applies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content');
            $table->string('title');
            $table->integer('union_id');
            $table->integer('user_id');
            $table->string('others');
            $table->string('tempName');
            $table->string('time');
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
        Schema::drop('union_applies');
    }
}
