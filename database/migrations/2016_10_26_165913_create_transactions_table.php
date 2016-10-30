<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('tran_id');            
            $table->integer('wallet_id_from')->nullable();//foreign key for WALLET table
            $table->integer('wallet_id_to');//foreign key for WALLET table
            $table->integer('currency_id');//foreign key for CURRENCY_DICT table
            $table->float('amount');
            $table->string('operation', 20);
            $table->dateTime('tran_date');
            $table->string('status', 6)->nullable();
            
            $table->index(['tran_id', 'status', 'wallet_id_from']);
        });
        
        /*Speed tuning*/
        /*Schema::table('transactions', function(Blueprint $table) {
            $table->foreign('wallet_id_from')->references('wallet_id')->on('wallets');
            $table->foreign('wallet_id_to')->references('wallet_id')->on('wallets');
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
        Schema::dropIfExists('transactions');
    }
}
