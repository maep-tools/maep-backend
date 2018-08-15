<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWPowCurveM2sTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_pow_curve_m2s', function (Blueprint $table) {
            $table->increments('id');
            $table->float('p', 15, 5)->nullable();
            $table->integer('wind_m2_config_id')->unsigned();
            $table->float('CT', 15, 5)->nullable();
            $table->integer('TpR')->nullable();
            $table->integer('process_id')->unsigned();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('wind_m2_config_id')->references('id')->on('wind_m2_configs')->onDelete('cascade');
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
        Schema::drop('w_pow_curve_m2s');
    }
}
