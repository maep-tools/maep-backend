<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSmallExpnsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('small_expns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('small_config_id')->unsigned();

            $table->integer('horizont_id')->unsigned();
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('cascade');       

            $table->float('max', 15,5)->nullable();
            $table->float('forced_unavailability', 18,8)->nullable();
            $table->float('historic_unavailability', 18,8)->nullable();
            $table->foreign('small_config_id')->references('id')->on('small_configs')->onDelete('cascade');
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
        Schema::drop('small_expns');
    }
}
