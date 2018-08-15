<?php

use Illuminate\Database\Seeder;

class ProcessesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('processes')->delete();
        
        \DB::table('processes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'MODELO EJEMPLO JOSE MARTES',
                'phase' => 1,
                'template' => 0,
                'blocks' => 20,
                'segment_id' => NULL,
                'generate_wind' => 0,
                'userId' => 1,
                'statusId' => 1,
                'templateId' => 1,
                'max_iter' => 1,
                'bnd_stages' => 1,
                'stages' => 2,
                'seriesBack' => 1,
                'seriesForw' => 1,
                'sensDem' => 1.0,
                'eps_area' => 0.5,
                'eps_all' => 0.5,
                'eps_risk' => 0.1,
                'commit' => 0.0,
                'read_data' => 0,
                'param_calculation' => 1,
                'param_opf' => 1,
                'wind_model2' => 0,
                'flow_gates' => 1,
                'lag_max' => 12,
                'testing_t' => 1,
                'd_correl' => 1,
                'seasonality' => 12,
                'created_at' => '2018-05-27 02:04:05',
                'updated_at' => '2018-05-27 02:25:12',
            ),
        ));
        
        
    }
}