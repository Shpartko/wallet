<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletHistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_hists', function (Blueprint $table) {
            $table->increments('hist_id');            
            $table->integer('wallet_id');//foreign key for WALLET table
            $table->integer('wallet_partner_id')->nullable();//foreign key for WALLET table
            $table->string('wallet_partner_name',255)->nullable();
            $table->integer('oper_id');//foreign key for OPERATION table
            $table->dateTime('hist_date');
            $table->string('type', 3);//In or OUT
            $table->bigInteger('amount');//client currency
            
            $table->index(['wallet_id', 'hist_date']);
        });
        
        /*Tune speed
        Schema::table('wallet_hists', function(Blueprint $table) {
            $table->foreign('wallet_id')->references('wallet_id')->on('wallets');            
            $table->foreign('wallet_partner_id')->references('wallet_id')->on('wallets');            
            $table->foreign('oper_id')->references('oper_id')->on('operations');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_hists');
    }
}
