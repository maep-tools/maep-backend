<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateinflowWindM2sTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inflow_wind_m2s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scenario')->unsigned();
            $table->integer('horizont_id')->unsigned();
            $table->integer('wind_config_id')->unsigned();
            $table->float('value', 15, 5)->nullable();
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('restrict');
            $table->foreign('wind_config_id')->references('id')->on('wind_m2_configs')->onDelete('cascade');
            $table->integer('process_id')->unsigned();
            $table->foreign('scenario')->references('id')->on('scenarios')->onDelete('cascade');
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
        Schema::drop('inflow_wind_m2s');
    }
}
