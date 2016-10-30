<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyDictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_dicts', function (Blueprint $table) {
            $table->increments('currency_id');            
            $table->string('currency_name', 60);      
            $table->string('currency', 3);//ISO code
            $table->smallInteger('fractional');
        });
        
        Schema::table('currency_dicts', function (Blueprint $table) {          
            $table->index('currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('currency_dicts');
    }
}
