<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStorageConfigsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->float('initial_storage', 18, 8)->nullable();
            $table->float('min_storage', 18, 8)->nullable();
            $table->float('max_storage',18,8)->nullable();
            $table->float('capacity', 15, 5)->nullable();
            $table->float('efficiency', 18, 8)->nullable();
            $table->float('max_outflow', 15, 5)->nullable();
            $table->string('portfolio')->nullable();
            $table->float('forced_unavailability', 18, 8)->nullable();
            $table->float('historic_unavailability', 18, 8)->nullable();
            $table->float('power_rate', 15, 5)->nullable();
            $table->integer('linked')->nullable();
            $table->integer('area_id')->unsigned();
            $table->integer('process_id')->unsigned();


            $table->integer('entrance_stage_date')->unsigned()->nullable();

            $table->foreign('entrance_stage_date')->references('id')->on('horizonts');            


            $table->integer('entrance_stage_id')->unsigned();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('entrance_stage_id')->references('id')->on('entrance_stages')->onDelete('restrict');
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
        Schema::drop('storage_configs');
    }
}
