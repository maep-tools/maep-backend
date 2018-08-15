<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();

        $this->call(RolesTableSeeder::class);
        $this->call(UserTableSeeder::class);

        //Model::reguard();
        $this->call(CategoriesTableSeeder::class);
        $this->call(EntranceStagesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        $this->call(MonthsTableSeeder::class);


/*        $this->call(ProcessesTableSeeder::class);
        $this->call(AreasTableSeeder::class);                

        $this->call(HorizontsTableSeeder::class); 


        $this->call(BlocksTableSeeder::class);
        $this->call(ScenariosTableSeeder::class);
        $this->call(SegmentsTableSeeder::class);
        

        $this->call(DemandsTableSeeder::class);
        $this->call(FuelCostPlantsTableSeeder::class);
        $this->call(FuelCostHorizontsTableSeeder::class);
        
        $this->call(HidroConfigsTableSeeder::class);
        $this->call(InflowsTableSeeder::class);
        



        
        $this->call(LinesTableSeeder::class);
        $this->call(LinesExpansionsTableSeeder::class);
        

        $this->call(WindConfigsTableSeeder::class);
        $this->call(WindExpnsTableSeeder::class);
        $this->call(SpeedIndicesTableSeeder::class);        
        $this->call(InflowWindsTableSeeder::class);        


        $this->call(RationingCostsTableSeeder::class);
        

        $this->call(SmallConfigsTableSeeder::class);


        $this->call(StorageConfigsTableSeeder::class);
        $this->call(StorageExpansionsTableSeeder::class);


        $this->call(ThermalConfigsTableSeeder::class);
        $this->call(ThermalExpnsTableSeeder::class);

        $this->call(WindM2ConfigsTableSeeder::class);
        $this->call(InflowWindM2sTableSeeder::class);        
        $this->call(WPowCurveM2sTableSeeder::class);
        $this->call(SpeedIndicesM2sTableSeeder::class);*/


    }
}


