<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHidroConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hidro_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('planta')->nullable();
            $table->float('initial_storage', 17, 5)->nullable();
            $table->float('min_storage', 17, 5)->nullable();
            $table->float('emision', 17, 5)->nullable();
            $table->float('max_storage', 17, 5)->nullable();
            $table->float('capacity', 15,5)->nullable();
            $table->float('prod_coefficient', 18, 5)->nullable();
            $table->float('max_turbining_outflow', 18,5)->nullable();
            $table->integer('entrance_stage_id')->unsigned()->nullable();

            $table->integer('entrance_stage_date')->unsigned()->nullable();

            $table->foreign('entrance_stage_date')->references('id')->on('horizonts');            


            $table->integer('initial_storage_stage')->nullable()->unsigned();
            $table->float('O&M', 10, 7)->nullable();
            $table->integer('area_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->float('forced_unavailability', 18, 5)->nullable();
            $table->float('historic_unavailability', 18,5)->nullable();
            $table->foreign('entrance_stage_id')->references('id')->on('entrance_stages')->onDelete('restrict');
            $table->foreign('initial_storage_stage')->references('id')->on('horizonts')->onDelete('set null');
            $table->integer('t_downstream_id')->unsigned()->nullable();
            $table->integer('s_downstream_id')->unsigned()->nullable();
            $table->foreign('t_downstream_id')->references('id')->on('hidro_configs')->onDelete('set null');
            $table->foreign('s_downstream_id')->references('id')->on('hidro_configs')->onDelete('set null');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('type_id')->references('id')->on('types');


            // estos datos son requeridos unicamente si se tienen que generar
            // datos eólicos en MAEP
            $table->float('p_instalada')->nullable();
            $table->float('fp')->nullable();
            $table->integer('uploaded_series')->nullable();


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
        Schema::drop('hidro_configs');
    }
}
