<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnionToTbizgroupuser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_biz_groupuser', function (Blueprint $table) {
            //
            $table->integer('FUNION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_biz_groupuser', function (Blueprint $table) {
            //
            $table->dropColumn('FUNION');
        });
    }
}
