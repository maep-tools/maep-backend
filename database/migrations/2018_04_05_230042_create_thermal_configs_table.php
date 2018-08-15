<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatethermalConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thermal_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('process_id')->unsigned();

            $table->float('capacity', 15, 5)->nullable();
            
            $table->integer('entrance_stage_id')->unsigned();

            $table->integer('entrance_stage_date')->unsigned()->nullable();

            
            $table->integer('type_id')->unsigned()->nullable();
            $table->integer('area_id')->unsigned();
            $table->integer('planta_fuel_id')->unsigned();
            
            $table->float('gen_min', 15,5)->nullable();
            $table->float('gen_max', 15,5)->nullable();
            $table->float('forced_unavailability', 18, 8)->nullable();
            $table->float('historic_unavailability', 18,8)->nullable();
            $table->float('O&MVariable', 15,5)->nullable();
            $table->float('heat_rate', 10, 5)->nullable();
            $table->float('emision', 10, 2)->nullable();
            
            $table->foreign('entrance_stage_id')->references('id')->on('entrance_stages');
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('planta_fuel_id')->references('id')->on('fuel_cost_plants');            
            $table->foreign('entrance_stage_date')->references('id')->on('horizonts');            


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
        Schema::drop('thermal_configs');
    }
}
