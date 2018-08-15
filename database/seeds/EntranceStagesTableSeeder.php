<?php

use Illuminate\Database\Seeder;

class EntranceStagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('entrance_stages')->delete();
        
        \DB::table('entrance_stages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'E',
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Existe',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'NE',
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'No entra',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'F',
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Fecha de entrada',
            ),
        ));
        
        
    }
}