<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('purchase_id');
            $table->string('product_id');
            $table->string('title');
            $table->string('size');
            $table->double('price');
            $table->integer('count');
            // the purchase product cover image url.
            $table->string('cover_url');
            // the total price of the purchase item.
            $table->double('total');
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
        Schema::drop('purchase_items');
    }
}
