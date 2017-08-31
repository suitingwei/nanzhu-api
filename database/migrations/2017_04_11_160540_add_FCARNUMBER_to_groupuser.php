<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFCARNUMBERToGroupuser extends Migration
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
            $table->string('FCARNUMBER');
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
            $table->dropColumn('FCARNUMBER');
        });
    }
}
