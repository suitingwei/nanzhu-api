<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnionIdToUnions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unions', function (Blueprint $table) {
            //
            $table->integer('union_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unions', function (Blueprint $table) {
            //
            $table->dropColumn('union_id');
        });
    }
}
