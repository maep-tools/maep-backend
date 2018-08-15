<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateprocessesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('phase');
            $table->boolean('template')->default(0);
            $table->integer('blocks')->default(10);
            $table->integer('segment_id')->unsigned()->nullable();
            
            $table->boolean('generate_wind');
            $table->integer('userId');
            $table->integer('statusId');
            $table->integer('templateId')->nullable();

            $table->string('type')->nullable();

            $table->integer('max_iter');
            $table->integer('bnd_stages');
            $table->integer('stages');
            $table->integer('seriesBack');
            $table->integer('seriesForw');
            $table->double('sensDem');
            $table->double('eps_area');
            $table->double('eps_all');
            $table->double('eps_risk');
            $table->double('commit');


            $table->boolean('read_data');
            $table->boolean('param_calculation');
            $table->boolean('param_opf');
            $table->boolean('wind_model2');
            $table->boolean('flow_gates');


            $table->integer('lag_max');
            $table->integer('testing_t');
            $table->integer('d_correl');
            $table->integer('seasonality');


            $table->unique(['name', 'userId']);

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
        Schema::drop('processes');
    }
}
