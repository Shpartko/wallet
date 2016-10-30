<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLastTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('last_transactions', function (Blueprint $table) {
            $table->integer('tran_id');//foreign key for TRANSACTIONS table
        });
        
        /*Speed tuning*/
        /*Schema::table('last_transactions', function(Blueprint $table) {
            $table->foreign('tran_id')->references('tran_id')->on('transactions');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('last_transactions');
    }
}
