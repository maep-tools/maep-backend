<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRationingCostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rationing_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('horizont_id')->unsigned();
            $table->integer('segment_id')->unsigned();
            $table->float('value', 15, 5)->nullable();

            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade');
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('cascade');

            $table->integer('process_id')->unsigned();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->unique(['process_id','horizont_id', 'segment_id']);
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
        Schema::drop('rationing_costs');
    }
}
