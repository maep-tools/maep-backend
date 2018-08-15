<?php

use Illuminate\Database\Seeder;

class RationingCostsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('rationing_costs')->delete();
        
        \DB::table('rationing_costs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'horizont_id' => 1,
                'segment_id' => 1,
                'value' => 12.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:08:16',
                'updated_at' => '2018-05-27 02:08:16',
            ),
            1 => 
            array (
                'id' => 2,
                'horizont_id' => 2,
                'segment_id' => 1,
                'value' => 12.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:08:16',
                'updated_at' => '2018-05-27 02:08:16',
            ),
            2 => 
            array (
                'id' => 3,
                'horizont_id' => 3,
                'segment_id' => 1,
                'value' => 2.0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:08:17',
                'updated_at' => '2018-05-27 02:08:17',
            ),
        ));
        
        
    }
}