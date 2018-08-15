<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSpeedIndicesM2sTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speed_indices_m2s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('month_id')->unsigned();
            $table->integer('wind_config_id')->unsigned();
            $table->integer('block_id')->unsigned();
            $table->float('value', 18, 8)->nullable();
            $table->integer('process_id')->unsigned();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');          
            $table->foreign('month_id')->references('id')->on('months');
            $table->foreign('wind_config_id')->references('id')->on('wind_m2_configs');
            $table->foreign('block_id')->references('id')->on('blocks');
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
        Schema::drop('speed_indices_m2s');
    }
}
