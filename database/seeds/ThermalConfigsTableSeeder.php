<?php

use Illuminate\Database\Seeder;

class ThermalConfigsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('thermal_configs')->delete();
        
        \DB::table('thermal_configs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'process_id' => 1,
                'capacity' => 140.0,
                'entrance_stage_id' => 1,
                'entrance_stage_date' => null,
                'type_id' => 1,
                'area_id' => 1,
                'planta_fuel_id' => 1,
                'gen_min' => 0.0,
                'gen_max' => 140.0,
                'forced_unavailability' => 0.0,
                'historic_unavailability' => 0.0,
                'O&MVariable' => 7.5,
                'heat_rate' => 1.0,
                'emision' => 1.0,
                'created_at' => '2018-05-27 02:06:58',
                'updated_at' => '2018-05-27 02:17:13',
                'name' => 'a',
            ),
        ));
        
        
    }
}