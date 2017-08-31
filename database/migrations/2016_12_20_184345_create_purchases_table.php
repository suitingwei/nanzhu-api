<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            //Purchase status.
            $table->boolean('paid');
            $table->boolean('shipped');
            $table->boolean('confirmed');
            $table->boolean('canceled');
            $table->boolean('deleted');
            //the frontend shown product searial number.DO NOT SHOW  the product id.
            $table->string('serial_number');
            // alipay,wechat or ebank
            $table->string('channel');

            //About express.
            $table->string('express_number');
            $table->double('express_price');
            $table->string('express_company');

            $table->double('total_items_price');
            $table->double('total_items_count');
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
        Schema::drop('purchases');
    }
}
