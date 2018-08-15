<?php

/*
|--------------------------------------------------------------------------
| Permissions
|--------------------------------------------------------------------------
|
| Permissions which pertain to roles, you can define access based on permissions
| with the permission middleware
| ex. ['middleware' => ['permission:super|admin|standard']]
*/


return [
    'admin' => 'createProcess,editProcess,deleteProcess,SeeProcess,changeConfiguration,administrate,seeUsers,editUsers,deleteUsers,createUsers,downloadUsers,seeRoles,editRoles,deleteRoles,createRoles,seeUser,seeStatistics,editUser,seeUserModels,generateWind,createTemplates,useTemplate,changeInitialConfigData,Experimental,areas,fuels,segments,blocks,scenarios,demands,fuelCosts,thermalConfigs,thermalExpansions,smallConfigs,smallExpansions,hydroConfigs,hydroExpansions,inflows,windConfigs,windExpansions,speedIndices,inflowWinds,storageConfigs,storageExpansions,lines,linesExpansions',
    'regular' => 'Regular',
    'editor' => 'Editor'
];
