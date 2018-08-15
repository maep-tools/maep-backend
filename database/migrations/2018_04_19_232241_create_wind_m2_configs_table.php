<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatewindM2ConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wind_m2_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_planta');
            $table->float('capacity', 15,5)->nullable();
            $table->float('losses', 18, 8)->nullable();
            $table->float('wSpeed_min', 15,5)->nullable();
            $table->float('speed_resolution', 15,5)->nullable();
            $table->float('wSpeed_max', 15,5)->nullable();
            $table->float('measuring_height', 18, 8)->nullable();
            $table->float('adjustment', 18, 8)->nullable();
            $table->float('hub_height', 15, 5)->nullable();
            $table->float('density', 15, 5)->nullable();
            $table->float('distance', 12, 2)->nullable();
            $table->float('diameter', 12, 2)->nullable();
            $table->string('speedDataMinutes')->nullable();
            $table->integer('process_id')->unsigned();
            $table->integer('area_id')->unsigned();
            $table->integer('entrance_stage_id')->unsigned();

            $table->integer('entrance_stage_date')->unsigned()->nullable();

            $table->foreign('entrance_stage_date')->references('id')->on('horizonts');            

            $table->foreign('entrance_stage_id')->references('id')->on('entrance_stages')->onDelete('restrict');
            $table->timestamps();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas');

            // estos datos son requeridos unicamente si se tienen que generar
            // datos eólicos en MAEP
            $table->float('p_instalada')->nullable();
            $table->float('fp')->nullable();
            $table->integer('resolucion')->nullable();
            $table->integer('localizacion')->nullable();



            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wind_m2_configs');
    }
}
