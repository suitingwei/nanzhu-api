<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id');
            $table->integer('user_id');
            $table->string('charge_id');
            $table->string('object');
            $table->string('created');
            $table->boolean('livemode');
            $table->boolean('paid');
            $table->boolean('refunded');
            $table->string('app');
            $table->string('channel');
            $table->string('order_no');
            $table->string('client_ip');
            $table->integer('amount');
            $table->integer('amount_settle');
            $table->string('currency');
            $table->string('subject');
            $table->string('body');
            $table->string('extra');
            $table->timestamp('time_paid');
            $table->timestamp('time_expire');
            $table->string('time_settle');
            $table->string('transaction_no');
            $table->string('refunds');
            $table->integer('amount_refunded');
            $table->string('failure_code');
            $table->string('failure_msg');
            $table->string('metadata');
            $table->string('credential');
            $table->string('description');
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
        Schema::drop('payments');
    }
}
