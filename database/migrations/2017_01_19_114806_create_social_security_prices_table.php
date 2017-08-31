<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocialSecurityPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_security_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('base_number');
            $table->integer('hukou_type');
            $table->double('pension_price'); //养老
            $table->double('medical_price'); //医疗
            $table->double('lost_job_price'); // 失业
            $table->double('work_accident_price'); /// 工伤
            $table->double('born_price'); //  生育
            $table->double('total_price');

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
        Schema::drop('social_security_prices');
    }
}
