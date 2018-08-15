<?php

use Illuminate\Database\Seeder;

class HidroConfigsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('hidro_configs')->delete();
        
        \DB::table('hidro_configs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'planta' => 'URRA',
                'initial_storage' => 200.0,
                'min_storage' => 0.0,
                'max_storage' => 500.0,
                'capacity' => 338.0,
                'prod_coefficient' => 1.0,
                'max_turbining_outflow' => 700.0,
                'entrance_stage_id' => 1,
                'entrance_stage_date' => '2018-05-27',
                'initial_storage_stage' => 3,
                'O&M' => 37.35,
                'area_id' => 1,
                'type_id' => 3,
                'forced_unavailability' => 0.36,
                'historic_unavailability' => 9.18,
                't_downstream_id' => NULL,
                's_downstream_id' => NULL,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:10:08',
                'updated_at' => '2018-05-27 02:19:17',
                'emision' => 1.0,
            ),
        ));
        
        
    }
}