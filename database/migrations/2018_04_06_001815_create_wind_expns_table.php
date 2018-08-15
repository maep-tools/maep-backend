<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatewindExpnsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wind_expns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wind_config_id')->unsigned();
            $table->float('capacity', 15, 5)->nullable();

            $table->integer('horizont_id')->unsigned();
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('cascade');       

            $table->float('efficiency', 18,8)->nullable();
            $table->integer('number_turbines')->nullable();
            $table->float('forced_unavailability', 18,8)->nullable();
            $table->float('historic_unavailability', 18,8)->nullable();
            $table->float('losses', 18,8)->nullable();
            $table->foreign('wind_config_id')->references('id')->on('wind_configs')->onDelete('cascade');
            $table->integer('process_id')->unsigned();
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
        Schema::drop('wind_expns');
    }
}
