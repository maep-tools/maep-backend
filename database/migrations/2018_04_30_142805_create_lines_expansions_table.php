<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatelinesExpansionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_expansions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('a_initial')->unsigned()->nullable();
            $table->integer('a_final')->unsigned()->nullable();
            $table->integer('line_id')->unsigned();
            $table->foreign('a_initial')->references('id')->on('areas')->nullable();
            $table->foreign('a_final')->references('id')->on('areas')->nullable();
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->integer('stage')->unsigned();
            $table->foreign('stage')->references('id')->on('horizonts')->nullable();            
            $table->float('a_b', 10, 5)->nullable();
            $table->float('b_ai', 10, 5)->nullable();
            $table->integer('process_id')->unsigned();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->float('efficiency', 8, 6)->nullable();
            $table->float('resistence', 10, 5)->nullable();
            $table->float('reactance', 10, 5)->nullable();
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
        Schema::drop('lines_expansions');
    }
}
