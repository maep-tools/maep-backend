<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatedemandsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('horizont_id')->unsigned();
            $table->integer('process_id')->unsigned();
            $table->integer('area_id')->unsigned();
            $table->float('factor', 18, 8)->nullable();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('horizont_id')->references('id')->on('horizonts')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->unique(['process_id','horizont_id', 'area_id']);
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
        Schema::drop('demands');
    }
}
