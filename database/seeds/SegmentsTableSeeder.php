<?php

use Illuminate\Database\Seeder;

class SegmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('segments')->delete();
        
        \DB::table('segments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Segmento 1',
                'process_id' => 1,
                'created_at' => '2018-05-27 02:04:17',
                'updated_at' => '2018-05-27 02:04:17',
            ),
        ));
        
        
    }
}