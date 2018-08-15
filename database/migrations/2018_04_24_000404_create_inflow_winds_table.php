<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateinflowWindsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inflow_winds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('horizont_id')->unsigned();
            $table->integer('scenario_id')->unsigned();
            $table->integer('wind_config_id')->unsigned();
            $table->float('value', 15, 5)->nullable();
            $table->integer('process_id')->unsigned();
            $table->foreign('horizont_id')->references('id')->on('horizonts');
            $table->foreign('scenario_id')->references('id')->on('scenarios');
            $table->foreign('wind_config_id')->references('id')->on('wind_configs');
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
        Schema::drop('inflow_winds');
    }
}
