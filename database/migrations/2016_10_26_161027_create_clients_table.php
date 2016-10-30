<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('client_id');            
            $table->string('name', 255)->unique();         
            $table->string('country', 60);//greatest length of country: full name of UK
            $table->string('city', 180);//greatest length of country: full name of Bangkok
            $table->dateTime('reg_date');
        });
        
        Schema::table('clients', function (Blueprint $table) {          
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
