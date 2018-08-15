<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSmallConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('small_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('planta_menor');
            $table->float('max', 15, 5)->nullable();
            $table->integer('entrance_stage_id')->unsigned();

            $table->integer('entrance_stage_date')->unsigned()->nullable();

            $table->foreign('entrance_stage_date')->references('id')->on('horizonts');            

            $table->integer('type_id')->unsigned();
            $table->integer('area_id')->unsigned();
            $table->float('gen_min', 15, 5)->nullable();
            $table->float('gen_max', 15, 5)->nullable();
            $table->float('forced_unavailability', 18, 8)->nullable();
            $table->float('historic_unavailability', 18, 8)->nullable();
            $table->foreign('entrance_stage_id')->references('id')->on('entrance_stages')->onDelete('restrict');
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('area_id')->references('id')->on('areas');
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
        Schema::drop('small_configs');
    }
}
