<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->increments('oper_id');            
            $table->integer('currency_id');//foreign key for CURRENCY_DICT table
            $table->string('operation',20);
            $table->dateTime('oper_date');
            $table->bigInteger('oper_amount');//amount in the transaction currency
            $table->bigInteger('usd_amount');//amount in USD
        });
        
        /*Tune speed
        Schema::table('operations', function(Blueprint $table) {
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
        Schema::dropIfExists('operations');
    }
}
