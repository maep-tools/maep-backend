<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlocksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->float('duration', 15, 5)->nullable();
            $table->float('participation', 18,8)->nullable();
            $table->boolean('storage_restrictions')->nullable();
            $table->integer('process_id')->unsigned();
            $table->unique(['name', 'process_id']);            
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
        Schema::drop('blocks');
    }
}
