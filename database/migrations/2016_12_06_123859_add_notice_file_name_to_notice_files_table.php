<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoticeFileNameToNoticeFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_biz_noticeexcelsinfo', function (Blueprint $table) {
            $table->string('custom_group_name'); //通告单文件的组名
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_biz_noticeexcelsinfo', function (Blueprint $table) {
            $table->dropColumn('custom_group_name');
        });
    }
}
