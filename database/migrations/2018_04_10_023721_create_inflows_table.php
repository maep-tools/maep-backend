<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateinflowsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inflows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scenario_id')->unsigned();
            $table->foreign('scenario_id')->references('id')->on('scenarios');
            $table->integer('horizont_id')->unsigned();
            $table->integer('hidro_config_id')->unsigned();
            $table->float('value', 15,5)->nullable();
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('restrict');
            $table->foreign('hidro_config_id')->references('id')->on('hidro_configs')->onDelete('cascade');
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
        Schema::drop('inflows');
    }
}
