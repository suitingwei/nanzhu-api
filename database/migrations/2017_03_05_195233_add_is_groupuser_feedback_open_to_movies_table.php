<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIsGroupuserFeedbackOpenToMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_biz_movie', function (Blueprint $table) {
            $table->boolean('is_groupuser_feedback_open');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_biz_movie', function (Blueprint $table) {
            $table->dropColumn('is_groupuser_feedback_open');
        });
    }
}
