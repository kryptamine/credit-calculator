<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditCalculatorTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::create('payment_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sum');
                $table->integer('range');
                $table->integer('rate');
                $table->integer('month');
                $table->integer('year');
                $table->timestamps();
            });

            Schema::create('payments', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('payment_options_id');
                $table->integer('month');
                $table->integer('year');
                $table->integer('position');
                $table->float('debt');
                $table->float('main_debt');
                $table->float('percent_payment');
                $table->float('credit_payment');
                $table->float('payment');

                $table->foreign('payment_options_id')->references('id')->on('payment_options');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_options');
        Schema::dropIfExists('payments');
    }
}
