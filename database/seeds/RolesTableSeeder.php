<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 11,
                'name' => 'Miembro',
                'label' => 'Miembro',
                'permissions' => 'createProcess,editProcess,deleteProcess,SeeProcess,createRoles,generateWind,useTemplate,changeInitialConfigData,areas,fuels,segments,blocks,scenarios,demands,fuelCosts,thermalConfigs,thermalExpansions,smallConfigs,smallExpansions,hydroConfigs,hydroExpansions,inflows,windConfigs,windExpansions,speedIndices,inflowWinds,storageConfigs,storageExpansions,lines,linesExpansions,rationingCost,InflowWindM2',
            ),
            1 => 
            array (
                'id' => 12,
                'name' => 'Administrador',
                'label' => 'Administrador',
                'permissions' => 'createProcess,editProcess,deleteProcess,SeeProcess,changeConfiguration,administrate,seeUsers,editUsers,deleteUsers,createUsers,downloadUsers,seeRoles,editRoles,deleteRoles,createRoles,seeUser,seeStatistics,editUser,seeUserModels,generateWind,createTemplates,useTemplate,changeInitialConfigData,Experimental,areas,fuels,segments,blocks,scenarios,demands,fuelCosts,thermalConfigs,thermalExpansions,smallConfigs,smallExpansions,hydroConfigs,hydroExpansions,inflows,windConfigs,windExpansions,speedIndices,inflowWinds,storageConfigs,storageExpansions,lines,linesExpansions,rationingCost,InflowWindM2'
            ),
        ));
        
        
    }
}