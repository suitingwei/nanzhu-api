<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocialSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_securities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id');
            $table->string('user_name');
            $table->string('user_phone');
            $table->integer('is_first');

            $table->integer('hukou_type');
            $table->string('hukou_address');

            $table->string('minority');

            $table->string('bank');
            $table->string('bank_card_number');

            $table->string('id_card_number');
            $table->string('id_card_up_image');
            $table->string('id_card_down_image');
            $table->string('id_card_photo');
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
        Schema::dropIfExists('social_securities');
    }
}
