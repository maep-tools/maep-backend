<?php

use Illuminate\Database\Seeder;

class DemandsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('entrance_stages')->delete();
        
        \DB::table('demands')->insert(array (
            0 => 
            array (
                'id' => 1,
                'horizont_id' => 1,
                'process_id' => 1,
                'area_id' => 1,
                'factor' => 10.0,
                'created_at' => '2018-05-27 02:05:49',
                'updated_at' => '2018-05-27 02:05:49',
            ),
            1 => 
            array (
                'id' => 2,
                'horizont_id' => 2,
                'process_id' => 1,
                'area_id' => 1,
                'factor' => 10.0,
                'created_at' => '2018-05-27 02:05:59',
                'updated_at' => '2018-05-27 02:05:59',
            ),
            2 => 
            array (
                'id' => 3,
                'horizont_id' => 3,
                'process_id' => 1,
                'area_id' => 1,
                'factor' => 10.0,
                'created_at' => '2018-05-27 02:06:00',
                'updated_at' => '2018-05-27 02:06:00',
            ),
        ));
        
        
    }
}