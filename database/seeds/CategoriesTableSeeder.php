<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('categories')->delete();
                
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Configuración',
                'parentId' => -1,
                'order' => 0,
                'component' => '',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Demanda',
                'parentId' => -1,
                'order' => 0,
                'component' => 'Demand',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Costo de combustible',
                'parentId' => -1,
                'order' => 0,
                'component' => 'FuelCost',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Plantas termicas',
                'parentId' => -1,
                'order' => 0,
                'component' => 'ThermalConfig',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Modificación plantas termicas',
                'parentId' => 4,
                'order' => 0,
                'component' => 'ThermalExpansion',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Racionamiento',
                'parentId' => -1,
                'order' => 0,
                'component' => 'RationingCosts',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Plantas menores',
                'parentId' => -1,
                'order' => 0,
                'component' => 'SmallConfig',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Modificación plantas menores',
                'parentId' => 7,
                'order' => 0,
                'component' => 'SmallExpansion',
                'disabled' => 1,
                'created_at' => '2018-04-03 15:26:01',
                'updated_at' => '2018-04-03 15:26:01',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Plantas hidroeléctricas',
                'parentId' => -1,
                'order' => 0,
                'component' => 'HydroConfig',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Modificación plantas hidroeléctricas',
                'parentId' => 9,
                'order' => 0,
                'component' => 'HydroExpansion',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Afluentes hidrícos',
                'parentId' => 9,
                'order' => 0,
                'component' => 'Inflow',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Plantas eólicas',
                'parentId' => -1,
                'order' => 0,
                'component' => 'WindConfig',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Modificación plantas eólicas',
                'parentId' => 12,
                'order' => 0,
                'component' => 'WindExpansion',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Áreas',
                'parentId' => 1,
                'order' => 0,
                'component' => 'Areas',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 17,
                'name' => 'Combustibles',
                'parentId' => 1,
                'order' => 0,
                'component' => 'Fuel',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 18,
                'name' => 'Segmentos de racionamiento',
                'parentId' => 1,
                'order' => 0,
                'component' => 'Segment',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 19,
                'name' => 'Bloques por etapa',
                'parentId' => 1,
                'order' => 0,
                'component' => 'Blocks',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 20,
                'name' => 'Indices de intensidad de viento',
                'parentId' => 12,
                'order' => 0,
                'component' => 'SpeedIndices',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 21,
                'name' => 'Velocidades de viento',
                'parentId' => 12,
                'order' => 0,
                'component' => 'InflowWind',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 22,
                'name' => 'Modelo 2 plantas eólicas',
                'parentId' => -1,
                'order' => 0,
                'component' => 'Wind_M2_config',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 23,
                'name' => 'Curva de potencia',
                'parentId' => 22,
                'order' => 0,
                'component' => 'WPowCurve',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 24,
                'name' => 'Modelo 2 indices de intensidad de viento',
                'parentId' => 22,
                'order' => 0,
                'component' => 'Speed_Indices_M2',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 25,
                'name' => 'Sistemas de almacenamiento',
                'parentId' => -1,
                'order' => 0,
                'component' => 'StorageConfig',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 26,
                'name' => 'Modificación sistemas de almacenamientio',
                'parentId' => 25,
                'order' => 0,
                'component' => 'StorageExpansion',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 27,
                'name' => 'Red de transmisión',
                'parentId' => -1,
                'order' => 0,
                'component' => 'Lines',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 28,
                'name' => 'Modificación red de transmisión',
                'parentId' => 27,
                'order' => 0,
                'component' => 'LinesExpansion',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 29,
                'name' => 'Escenarios',
                'parentId' => 1,
                'order' => 0,
                'component' => 'Scenario',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 30,
                'name' => 'Modelo 2 Velocidades de viento',
                'parentId' => 22,
                'order' => 0,
                'component' => 'InflowWindM2',
                'disabled' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}