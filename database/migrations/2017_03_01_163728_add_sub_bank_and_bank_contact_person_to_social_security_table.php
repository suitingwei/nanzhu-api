<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubBankAndBankContactPersonToSocialSecurityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_securities', function (Blueprint $table) {
            $table->string('sub_bank');
            $table->string('bank_contact_person');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_securities', function (Blueprint $table) {
            $table->dropColumn('sub_bank');
            $table->dropColumn('bank_contact_person');
        });
    }
}
