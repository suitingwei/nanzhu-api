<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanidToNoticeFileReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notice_file_receivers', function (Blueprint $table) {
            $table->integer('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notice_file_receivers', function (Blueprint $table) {
            $table->dropColumn('plan_id');
        });
    }
}
