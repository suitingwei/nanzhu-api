<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientMovieRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('client_movie_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default('');         // client or movie side.
            $table->string('invest_types')->default(''); // investment types  植入,投资,赞助...
            $table->string('movie_types')->default('');  // movie types  网大,电影,电视剧
            $table->string('reward_types')->default(''); // movie types  网大,电影,电视剧
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->double('budget_bottom')->default(0.0);
            $table->double('budget_top')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('client_movie_requirements');
    }
}
