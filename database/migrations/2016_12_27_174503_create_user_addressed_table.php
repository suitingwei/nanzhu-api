<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->increments('id');

            //The creator of this address.
            $table->integer('user_id');

            //We must consider that the creator of the address may not be the receiver.
            $table->string('receiver_name');
            $table->string('receiver_phone');

            $table->string('province');
            $table->string('city');
            $table->string('area');
            $table->string('detail');

            //Is the address the default address of the creator's all addresses.
            $table->integer('is_default');
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
        Schema::drop('user_addresses');
    }
}
