<?php

use Illuminate\Database\Seeder;

class BlocksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('categories')->delete();
        
        \DB::table('blocks')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Bloque 1',
                'duration' => 1.0,
                'participation' => 1.0,
                'storage_restrictions' => 0,
                'process_id' => 1,
                'created_at' => '2018-05-27 02:04:05',
                'updated_at' => '2018-05-27 02:23:53',
            ),
        ));
        
        
    }
}