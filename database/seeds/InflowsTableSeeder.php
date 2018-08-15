<?php

use Illuminate\Database\Seeder;

class InflowsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('inflows')->delete();
        
        \DB::table('inflows')->insert(array (
            0 => 
            array (
                'id' => 1,
                'scenario_id' => 1,
                'horizont_id' => 1,
                'hidro_config_id' => 1,
                'value' => 1.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:12:44',
                'updated_at' => '2018-05-27 02:12:44',
            ),
            1 => 
            array (
                'id' => 2,
                'scenario_id' => 1,
                'horizont_id' => 2,
                'hidro_config_id' => 1,
                'value' => 2.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:12:44',
                'updated_at' => '2018-05-27 02:12:44',
            ),
            2 => 
            array (
                'id' => 3,
                'scenario_id' => 1,
                'horizont_id' => 3,
                'hidro_config_id' => 1,
                'value' => 2.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:12:45',
                'updated_at' => '2018-05-27 02:12:45',
            ),
            3 => 
            array (
                'id' => 4,
                'scenario_id' => 2,
                'horizont_id' => 1,
                'hidro_config_id' => 1,
                'value' => 1.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:12:45',
                'updated_at' => '2018-05-27 02:12:45',
            ),
            4 => 
            array (
                'id' => 5,
                'scenario_id' => 2,
                'horizont_id' => 2,
                'hidro_config_id' => 1,
                'value' => 2.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:12:46',
                'updated_at' => '2018-05-27 02:12:46',
            ),
            5 => 
            array (
                'id' => 6,
                'scenario_id' => 2,
                'horizont_id' => 3,
                'hidro_config_id' => 1,
                'value' => 2.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:12:47',
                'updated_at' => '2018-05-27 02:12:47',
            ),
        ));
        
        
    }
}