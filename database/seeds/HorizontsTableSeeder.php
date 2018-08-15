<?php

use Illuminate\Database\Seeder;

class HorizontsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('horizonts')->delete();
        
        \DB::table('horizonts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'process_id' => 1,
                'period' => '2018-03-01',
                'national' => 1.0,
                'created_at' => '2018-05-27 02:04:28',
                'updated_at' => '2018-05-27 02:06:14',
            ),
            1 => 
            array (
                'id' => 2,
                'process_id' => 1,
                'period' => '2018-02-01',
                'national' => 1.0,
                'created_at' => '2018-05-27 02:04:28',
                'updated_at' => '2018-05-27 02:06:15',
            ),
            2 => 
            array (
                'id' => 3,
                'process_id' => 1,
                'period' => '2018-01-01',
                'national' => 1.0,
                'created_at' => '2018-05-27 02:04:28',
                'updated_at' => '2018-05-27 02:06:15',
            ),
        ));
        
        
    }
}