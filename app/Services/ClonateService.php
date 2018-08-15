<?php

namespace App\Services;

class ClonateService
{
    /**
     * Send the current user a new activation token
     *
     * @return bool
     */
    public function clonate($name, $idModelToClone, $userToClone)
    {
        $model = \App\Models\Process::find($idModelToClone);
        $model->load('Areas', 'Blocks', 'Demand', "FuelCostPlant", "HidroConfig", "HidroExpn", "Horizont", "Inflow", "InflowWind", "InflowWindM2", "Lines", "linesExpansion","RationingCost","Scenario","Segment","SmallConfig","SmallExpn","SpeedIndices","SpeedIndicesM2","StorageConfig","StorageExpansion","ThermalExpn","ThermalConfig","WindConfig","WindExpn","WindM2Config","WPowCurveM2");
            
            // iniciamos clonando el modelo
            $newModel = $model->replicate();
            $newModel->name = $name;
            $model->template = false;
            $model->templateId = null;
            $newModel->userId = $userToClone;
            $newModel->push();

            // continuamos clonando todo lo que tiene el modelo
            foreach($model->getRelations() as $relation => $items){
                foreach($items as $item){
                    unset($item->id);
                    $newModel->{$relation}()->create($item->toArray());
                }
            }


            /*/ Clonamos los costos de combustible */

            $fuelCostPlantsOld = \App\Models\FuelCostPlant::where('process_id','=',$idModelToClone)->get();

            foreach ($fuelCostPlantsOld as $fuelCostPlantOld) {
                $fuelHorizontsOld = \App\Models\FuelCostHorizont::where('planta_fuel_id','=',$fuelCostPlantOld->id)->get();

                foreach ($fuelHorizontsOld as $fuelHorizontNew) {

                    // buscamos el registro intermedio que tiene identificador invalid
                    $horizontOld = \App\Models\Horizont::where([['id', '=', $fuelHorizontNew->horizont_id]])->first();

                    $horizontNew = \App\Models\Horizont::where('process_id', '=', $newModel->id)->where('period', '=', $horizontOld->period)->first();

                    $plantNew = \App\Models\FuelCostPlant::where('process_id','=',$newModel->id)
                        ->where('name', '=', $fuelCostPlantOld->name)
                        ->first();
                    
                    \App\Models\FuelCostHorizont::create([
                        'planta_fuel_id' => $plantNew->id,
                        'horizont_id' => $horizontNew->id,
                        'value' => $fuelHorizontNew->value
                    ]);
                }
            }

            // ahora hay que hacer actualizaciones a todo lo que tenga intermedias viejas

            //En demanda debemos actualizar todos los horizontes con los identificadores nuevos
            //Para ello obtenemos la demanda
            $demandsNew = \App\Models\Demand::where('process_id', '=', $newModel->id)->get();
            // recorremos todos los datos nuevos
            foreach ($demandsNew as $key => $demand) {
                // buscamos el registro intermedio que tiene identificador invalid
                $horizontOld = \App\Models\Horizont::where([['id', '=', $demand->horizont_id]])->first();
                if ($horizontOld) {
                    $horizontNew = \App\Models\Horizont::where('process_id', '=', $demand->process_id)->where('period', '=', $horizontOld->period)->first();                
                    $demand->horizont_id = $horizontNew->id;
                }

                $areasOld = \App\Models\Areas::where([['id', '=', $demand->area_id]])->first();
               
                if ($areasOld) {
                    $areasNew = \App\Models\Areas::where('process_id', '=', $demand->process_id)->where('name', '=', $areasOld->name)->first();
                    // asignamos los nuevos valores
                    $demand->area_id = $areasNew->id;
                }

                $demand->update($demand->toArray());
            }


            //1. a hidro config toca actualizarle 
                // entrance_stage_date
                // initial_storage_state
                // t_downstream_id
                // s_downstream_id
                // area_id

            $hidroNews = \App\Models\HidroConfig::where('process_id', '=', $newModel->id)->get();
            // recorremos todos los datos nuevos
            foreach ($hidroNews as $key => $hidroNew) {
            // buscamos el registro intermedio que tiene identificador invalid pero que se acaba 
            // de agregar
            $horizontOldEntranceStageDate = \App\Models\Horizont::where('id', '=', $hidroNew->entrance_stage_date)->first();
            
            if ($horizontOldEntranceStageDate) {
                // buscamos el nuevo
                $horizontNewEntranceStageDate = \App\Models\Horizont::where('process_id', '=', $hidroNew->process_id)->where('period', '=', $horizontOldEntranceStageDate->period)->first();
           
                $hidroNew->entrance_stage_date = $horizontNewEntranceStageDate->id;
            }
           

            $initialStorageStageOldDate = \App\Models\Horizont::where('id', '=', $hidroNew->initial_storage_stage)->first();
            
            if ($initialStorageStageOldDate) {
                $horizontInitialStorageStageDate = \App\Models\Horizont::where('process_id', '=', $hidroNew->process_id)->where('period', '=', $initialStorageStageOldDate->period)->first();

                $hidroNew->initial_storage_stage = $horizontInitialStorageStageDate->id;

            }


            $areasOld = \App\Models\Areas::where([['id', '=', $hidroNew->area_id]])->first();
            if ($areasOld) {
                $areasNew = \App\Models\Areas::where('process_id', '=', $hidroNew->process_id)->where('name', '=', $areasOld->name)->first();
                $hidroNew->area_id = $areasNew->id;
            }



            // En el caso de que tenga aguas abajo y derramamiento aguasabajo
            $t_downstreamOld = \App\Models\HidroConfig::where('id', '=', $hidroNew->t_downstream_id)->first();

            if($t_downstreamOld) {
                $t_downstreamNew = \App\Models\HidroConfig::where('process_id', '=', $hidroNew->process_id)->where('planta', '=', $t_downstreamOld->planta)->first();
                $hidroNew->t_downstream_id = $t_downstreamNew->id;

            }


            // En el caso de que tenga aguas abajo y derramamiento aguasabajo
            $s_downstreamOld = \App\Models\HidroConfig::where('id', '=', $hidroNew->s_downstream_id)->first();
            if($s_downstreamOld) {
                $s_downstreamNew = \App\Models\HidroConfig::where('process_id', '=', $hidroNew->process_id)->where('planta', '=', $s_downstreamOld->planta)->first();
                $hidroNew->s_downstream_id = $s_downstreamNew->id;

            }
   
            $hidroNew->update($hidroNew->toArray());
         }



            //1. a la expansion hay que actualizarle el hydroConfigId

            $hidroExpns = \App\Models\HidroExpn::where('process_id', '=', $newModel->id)->get();

            foreach ($hidroExpns as $hydroExpn) {
                // buscamos el registro intermedio que tiene identificador invalid
                $horizontOld = \App\Models\Horizont::where([['id', '=', $hydroExpn->horizont_id]])->first();
                if ($horizontOld) {
                    $horizontNew = \App\Models\Horizont::where('process_id', '=', $hydroExpn->process_id)->where('period', '=', $horizontOld->period)->first();
                    $hydroExpn->horizont_id = $horizontNew->id;
                }


                $hydroConfig = \App\Models\HidroConfig::where([['id', '=', $hydroExpn->hidro_config_id]])->first();
                if ($hydroConfig) {
                    $hydroConfigNew = \App\Models\HidroConfig::where('process_id', '=', $hydroExpn->process_id)->where('planta', '=', $hydroConfig->planta)->first();
                    $hydroExpn->hidro_config_id = $hydroConfigNew->id;
                }

                $hydroExpn->update($hydroExpn->toArray());
            }



            //2. a inflowWindM2 hay que actualizarle el scenario
            //2. a el horizont_id, el wind_config_id
            //3. a inflowWind hay que actualizarle horizont_id, scenario_id, wind_config_id
            //3 a inflows hay que actualizarle scenario_id, horizont_id, hidro_config_id
            //4 a lines a_initial, b_final
            //4 a lines_expn line_id
            //5 a rationing_costs horizont_id, segment_id
            //5 a small_configs entrance_stage_date, area_id
            //6 a small_expansion horizont_id,small_config_id
            //6 a speed_indices wind_config_id, block_id
            //7 a speed_indices_m2s wind_config_id, block_id
            //7 a storage_configs entrance_stage_date, area_id, 
            //8 a storage_expansion storage_config_id, horizont_id
            //8 a thermal_configs entrance_stage_date, area_id, planta_fuel_id
            //9 a thermal_expns thermal_config_id, horizont_id
            //9 a w_pow_curve_m2s wind_m2_config_id
            //10 a windConfig initial_storage_stage, entrance_stage_date,area_id
            //10 a wind expansion wind_config_id, horizont_id
            //11 a wind_m2_configs area_id, entrance_stage_date


            return $newModel;


    }
}
