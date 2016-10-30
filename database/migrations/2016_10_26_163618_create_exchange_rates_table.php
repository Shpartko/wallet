<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->increments('rate_id');            
            $table->integer('currency_id');//foreign key for CURRENCY_DICT table
            $table->double('rate');
            $table->date('rate_date');
            $table->dateTime('update_date');
            
            $table->index(['rate_date','currency_id']);
        });
        
        /*Tune speed
        Schema::table('exchange_rates', function(Blueprint $table) {
            $table->foreign('currency_id')->references('currency_id')->on('currency_dicts');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
}
