<?php

use Illuminate\Database\Seeder;

class FuelCostPlantsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('fuel_cost_plants')->delete();
        
        \DB::table('fuel_cost_plants')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Combustible 1',
                'process_id' => 1,
                'created_at' => '2018-05-27 02:04:15',
                'updated_at' => '2018-05-27 02:04:15',
            ),
        ));
        
        
    }
}