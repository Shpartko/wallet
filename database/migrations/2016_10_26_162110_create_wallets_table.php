<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('wallet_id'); 
            $table->integer('client_id');//foreign key for CLIENT table
            $table->integer('currency_id');//foreign key for CURRENCY_DICT table
            $table->bigInteger('balance');//clinet currency
            
            $table->index('client_id');
        });
        
        /*Tune speed
        Schema::table('wallets', function(Blueprint $table) {
            $table->foreign('client_id')->references('client_id')->on('clients');
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
        Schema::dropIfExists('WALLET');
    }
}
