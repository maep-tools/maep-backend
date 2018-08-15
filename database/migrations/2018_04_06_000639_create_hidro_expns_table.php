<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHidroExpnsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hidro_expns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hidro_config_id')->unsigned();
            $table->float('capacity', 15, 5)->nullable();
            $table->float('prod_coefficient', 18, 5)->nullable();
            $table->float('max_turbing_outflow', 15, 5)->nullable();
            $table->integer('horizont_id')->unsigned();
            $table->float('forced_unavailability', 18,5)->nullable();
            $table->float('emision', 18,8)->nullable();            
            $table->float('historic_unavailability',18,5)->nullable();
            $table->float('max_storage', 15, 5)->nullable();
            $table->foreign('hidro_config_id')->references('id')->on('hidro_configs')->onDelete('cascade');
            $table->integer('process_id')->unsigned();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('cascade');       
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
        Schema::drop('hidro_expns');
    }
}
