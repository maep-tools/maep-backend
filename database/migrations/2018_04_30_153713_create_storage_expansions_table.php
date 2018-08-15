<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatestorageExpansionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_expansions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('storage_config_id')->unsigned();


            $table->integer('horizont_id')->unsigned();
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('cascade');       

            $table->float('capacity', 10,5)->nullable(); // capacidad
            $table->float('efficiency', 8, 5)->nullable(); // eficiencia
            $table->float('max_outflow',8,5)->nullable(); // maxima descarga
            $table->float('forced_unavailability', 8, 6)->nullable();
            $table->float('historic_unavailability', 8, 6)->nullable();
            $table->integer('process_id')->unsigned();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('storage_config_id')->references('id')->on('storage_configs')->onDelete('cascade');
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
        Schema::drop('storage_expansions');
    }
}
