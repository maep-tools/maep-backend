<?php

use Illuminate\Database\Seeder;

class FuelCostHorizontsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('fuel_cost_horizonts')->delete();
        
        \DB::table('fuel_cost_horizonts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'value' => 1.0,
                'planta_fuel_id' => 1,
                'horizont_id' => 1,
                'created_at' => '2018-05-27 02:06:49',
                'updated_at' => '2018-05-27 02:06:49',
            ),
            1 => 
            array (
                'id' => 2,
                'value' => 2.0,
                'planta_fuel_id' => 1,
                'horizont_id' => 2,
                'created_at' => '2018-05-27 02:06:49',
                'updated_at' => '2018-05-27 02:06:49',
            ),
            2 => 
            array (
                'id' => 3,
                'value' => 1.0,
                'planta_fuel_id' => 1,
                'horizont_id' => 3,
                'created_at' => '2018-05-27 02:06:50',
                'updated_at' => '2018-05-27 02:06:50',
            ),
        ));
        
        
    }
}