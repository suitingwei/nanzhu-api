<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_addresses', function (Blueprint $table) {
            $table->increments('id');
            //The creator of this address.
            $table->integer('user_id');
            $table->integer('purchase_id');

            //We must consider that the creator of the address may not be the receiver.
            $table->string('receiver_name');
            $table->string('receiver_phone');

            $table->string('province');
            $table->string('city');
            $table->string('area');
            $table->string('detail');

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
        Schema::drop('purchase_addresses');
    }
}
