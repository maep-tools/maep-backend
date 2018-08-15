<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFuelCostHorizontsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_cost_horizonts', function (Blueprint $table) {
            $table->increments('id');
            $table->float('value', 15, 5)->nullable();
            
            $table->integer('planta_fuel_id')->unsigned();
            $table->foreign('planta_fuel_id')->references('id')->on('fuel_cost_plants')->onDelete('cascade');

            $table->integer('horizont_id')->unsigned();
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('cascade');
            
            $table->unique(['horizont_id', 'planta_fuel_id']);            
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
        Schema::drop('fuel_cost_horizonts');
    }
}
