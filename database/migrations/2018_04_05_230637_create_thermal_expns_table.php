<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThermalExpnsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thermal_expns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('thermal_config_id')->unsigned();
            $table->integer('horizont_id')->unsigned();
            $table->float('max', 15, 5)->nullable();
            $table->float('gen_min', 15, 5)->nullable();
            $table->float('gen_max', 15,5)->nullable();
            $table->float('forced_unavailability', 15,8)->nullable();
            $table->float('historic_unavailability', 15,8)->nullable();
            

            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('cascade');       
            $table->foreign('thermal_config_id')->references('id')->on('thermal_configs')->onDelete('cascade');
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
        Schema::drop('thermal_expns');
    }
}
