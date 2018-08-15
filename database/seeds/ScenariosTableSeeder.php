<?php

use Illuminate\Database\Seeder;

class ScenariosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('scenarios')->delete();
        
        \DB::table('scenarios')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Escenario 1',
                'process_id' => 1,
                'created_at' => '2018-05-27 02:04:05',
                'updated_at' => '2018-05-27 02:04:05',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Escenario 2',
                'process_id' => 1,
                'created_at' => '2018-05-27 02:04:05',
                'updated_at' => '2018-05-27 02:04:05',
            ),
        ));
        
        
    }
}