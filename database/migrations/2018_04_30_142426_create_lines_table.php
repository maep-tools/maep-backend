<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatelinesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('a_initial')->unsigned()->nullable();
            $table->integer('b_final')->unsigned()->nullable();
            $table->float('a_to_b', 10,5)->nullable();
            $table->float('b_to_a', 10,5)->nullable();
            $table->float('efficiency', 8, 6)->nullable();
            $table->float('resistence', 10, 5)->nullable(); // resistencia
            $table->float('reactance', 10, 5)->nullable(); // reactancia
            $table->foreign('a_initial')->references('id')->on('areas');
            $table->foreign('b_final')->references('id')->on('areas');
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
        Schema::drop('lines');
    }
}
