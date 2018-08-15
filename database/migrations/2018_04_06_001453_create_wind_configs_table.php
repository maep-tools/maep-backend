<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatewindConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wind_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('planta')->nullable();
            $table->float('capacity', 15, 5)->nullable();
            $table->float('losses', 18, 8)->nullable();
            $table->float('density', 15, 5)->nullable();
            $table->float('efficiency', 18, 8)->nullable();
            $table->float('diameter', 15,5)->nullable();
            $table->float('speed_rated', 15, 5)->nullable();
            $table->integer('entrance_stage_id')->unsigned();
            $table->integer('initial_storage_stage')->nullable()->unsigned();
            $table->integer('area_id')->unsigned();
            $table->float('forced_unavailability', 18,8 )->nullable();
            $table->float('variability', 15, 5)->nullable();
            $table->float('speed_in', 15, 5)->nullable();
            $table->float('speed_out', 15, 5)->nullable();
            $table->float('betz_limit', 18, 8)->nullable();
            $table->foreign('initial_storage_stage')->references('id')->on('horizonts')->onDelete('set null');            
            $table->foreign('entrance_stage_id')->references('id')->on('entrance_stages')->onDelete('restrict');


            $table->integer('entrance_stage_date')->unsigned()->nullable();

            $table->foreign('entrance_stage_date')->references('id')->on('horizonts');            


            $table->foreign('area_id')->references('id')->on('areas');
            $table->integer('process_id')->unsigned();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');



            // estos datos son requeridos unicamente si se tienen que generar
            // datos eólicos en MAEP
            $table->float('p_instalada')->nullable();
            $table->float('fp')->nullable();
            $table->integer('uploaded_series')->nullable();




            
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
        Schema::drop('wind_configs');
    }
}
