<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowandreveiverToMoviebasements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_basements', function (Blueprint $table) {
            //
            $table->integer('allow_cooperation');
            $table->string('receive_user_ids');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_basements', function (Blueprint $table) {
            //
            $table->dropColumn('allow_cooperation');
            $table->dropColumn('receive_user_ids');
        });
    }
}
