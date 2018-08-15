<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateThermalConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thermal_configs', function($table) {
            $table->string('name');
            $table->unique(['name', 'process_id']);
        });


        Schema::table('processes', function($table) {
            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('set null');   
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thermal_configs', function($table) {
            $table->dropColumn('name');
            $table->dropColumn('entrance_stage_date');
        });

        Schema::table('processes', function($table) {
            $table->dropForeign('processes_segment_id_foreign');     
        });


    }
}
