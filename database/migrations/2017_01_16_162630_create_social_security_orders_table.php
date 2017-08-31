<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocialSecurityOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_security_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('channel');

            $table->integer('social_security_id');
            $table->boolean('is_first');
            $table->integer('creator_id');

            //About user info snap shoot.
            $table->string('user_name');
            $table->string('user_phone');
            $table->string('hukou_type');
            $table->string('hukou_address');
            $table->string('minority');
            $table->string('bank');
            $table->string('bank_card_number');

            //About order status.
            $table->integer('paid');
            $table->integer('canceled');

            /**
             *  paid == false                                       代付款
             *  paid == true &&  end_date >= time()                 参保中
             *  paid == true **  time() > end_date                  已完成
             *  canceled == true                                    已取消
             *
             */

            //About order price.
            $table->double('pension_price');
            $table->double('lost_job_price');
            $table->double('work_accident_price');
            $table->double('born_price');
            $table->double('medical_price');
            $table->double('social_security_price');
            $table->double('service_price');
            $table->double('total_price');

            //About order start,end,lasting days.
            $table->string('base_number');
            $table->timestamps();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->double('cost_months');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('social_security_orders');
    }
}
