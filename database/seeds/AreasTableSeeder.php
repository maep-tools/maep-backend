<?php

use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('areas')->delete();
        
        \DB::table('areas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'process_id' => 1,
                'name' => 'Area 1',
                'created_at' => '2018-05-27 02:04:14',
                'updated_at' => '2018-05-27 02:04:14',
            ),
        ));
        
        
    }
}