<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFuelCostPlantsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_cost_plants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('process_id')->unsigned();
            $table->unique(['name', 'process_id']);            
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');         
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
        Schema::drop('fuel_cost_plants');
    }
}
