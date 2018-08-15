<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use App\Models\Areas;
use App\Models\ThermalConfig;
use App\Models\Thermal_expn;
use App\Models\FuelCostHorizont;
use App\Models\Horizont;
use App\Models\RationingCost;
use App\Models\SmallConfig;
use App\Models\SmallExpn;

use App\Models\hydroConfig;
use App\Models\hydroExpansion;
use App\Models\Inflow;
use App\Models\WindConfig;
use App\Models\WindExpn;
use App\Models\SpeedIndices;
use App\Models\InflowWind;
use App\Models\windM2Config;
use App\Models\InflowWindM2;
use App\Models\WPowCurveM2;
use App\Models\SpeedIndicesM2;
use App\Models\StorageConfig;
use App\Models\StorageExpn;
use App\Models\Lines;
use App\Models\FuelCostPlant;
use App\Models\linesExpansion;
use App\Models\Demand;
use App\Models\Blocks;
use App\Models\Month;
use App\Models\Segment;
use App\Models\HidroConfig;
use App\Models\Infow;
use App\Models\Scenario;
use App\models\Process;

class GenerateExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Identificador del proceso
     *
     * @return void
     */
    protected $process_id;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($process_id)
    {
        $this->process_id = $process_id;
        // obtenemos el id del proceso
    }

    public function moveElement(&$array, $a, $b) {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
        return $array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // accedemos a las areas (no hace falta hacer join)
        $areas = Areas::where('process_id', '=', $this->process_id)->get();

        $months = Month::get();
        $this->data = Process::where('id', '=',$this->process_id)->first();


        //scenarios
        $scenarios =  Scenario::where('scenarios.process_id', '=', $this->process_id)->get();

        // capturamos los horizontes
        $horizonts = Horizont::where('horizonts.process_id', '=', $this->process_id)->orderBy('id', 'desc')->get();
        // accedemos a la configuración termica (hace falta hacer join con:)
            // entrance stage
            // tipo
            // area
            // fuel


        $thermalConfig = ThermalConfig::join('types', 'types.id', '=', 'thermal_configs.type_id')
            ->join('areas', 'areas.id', '=', 'thermal_configs.area_id')
            ->leftJoin('horizonts', 'horizonts.id', '=', 'thermal_configs.entrance_stage_date')

            ->join('fuel_cost_plants', 'fuel_cost_plants.id', '=', 'thermal_configs.planta_fuel_id')
            ->join('entrance_stages', 'entrance_stages.id', 'thermal_configs.entrance_stage_id')
            ->where('thermal_configs.process_id', '=', $this->process_id)->select('thermal_configs.capacity', 'thermal_configs.gen_min', 'thermal_configs.gen_max', 'thermal_configs.forced_unavailability','thermal_configs.historic_unavailability', 'thermal_configs.O&MVariable', 'thermal_configs.heat_rate', 'thermal_configs.emision', 'thermal_configs.name' , 'types.name as typeName', 'areas.name as areaName', 'fuel_cost_plants.name as fuelName', 'entrance_stages.name as entranceName', 'thermal_configs.entrance_stage_date', 'horizonts.period')->get();

        // accedemos a la expansion termica (hace falta hacer join con:)
        // la configuracion termica
        // el horizonte

        $thermalExpn = Thermal_expn::join('thermal_configs', 'thermal_configs.id', '=', 'thermal_expns.thermal_config_id')
            ->join('horizonts', 'horizonts.id', '=', 'thermal_expns.horizont_id')
            ->where('thermal_configs.process_id', '=', $this->process_id)
            ->select('thermal_expns.*', 'thermal_configs.name', 'horizonts.period')
            ->get();



        // fuel cost 
            // leftjoin con horizonte
            // join con planta

        $fuelCostHorizonts = Horizont::leftJoin('fuel_cost_horizonts', 'fuel_cost_horizonts.horizont_id', 'horizonts.id')
            ->leftJoin('fuel_cost_plants', 'fuel_cost_plants.id', 'fuel_cost_horizonts.planta_fuel_id')
            ->select('horizonts.process_id', 'horizonts.period', 'fuel_cost_horizonts.*', 'fuel_cost_plants.name as namePlant')
            ->where('horizonts.process_id', '=', $this->process_id)
            ->get();


        $fuelsNames = \App\Models\FuelCostPlant::join('fuel_cost_horizonts', 'fuel_cost_plants.id', '=', 'fuel_cost_horizonts.planta_fuel_id')
                ->select('name')
                ->where('fuel_cost_plants.process_id', '=', $this->process_id)->groupBy('fuel_cost_plants.name')->get();
            

        // rationing cost
            // join con segmentos

        $rationingCost = Horizont::leftJoin('rationing_costs', 'rationing_costs.horizont_id', 'horizonts.id')
            ->leftJoin('segments', 'segments.id', 'rationing_costs.segment_id')
            ->select('horizonts.process_id', 'horizonts.period', 'rationing_costs.*', 'segments.name as nameSegment')
            ->where('horizonts.process_id', '=', $this->process_id)
            ->get();


        //SmallConfig
            // join con: entrance_stage, tipo, area

        $smallConfig = Areas::join('small_configs', 'small_configs.area_id', 'areas.id')
            ->leftJoin('horizonts', 'horizonts.id', '=', 'small_configs.entrance_stage_date')
            ->join('entrance_stages', 'entrance_stages.id', 'small_configs.entrance_stage_id')
            ->join('types', 'types.id', '=', 'small_configs.type_id')
            ->select( 'horizonts.period', 'small_configs.*', 'entrance_stages.name as entranceName', 'types.name as typeName','areas.name as areaName')
            ->where('small_configs.process_id', '=', $this->process_id)
            ->get();



        //SmallExpansion
            //join con: horizonte y con SmallConfig
        $SmallExpn = Horizont::join('small_expns', 'small_expns.horizont_id', 'horizonts.id')
            ->join('small_configs', 'small_configs.id', '=', 'small_expns.small_config_id')
            ->select('small_expns.*', 'horizonts.period', 'small_configs.planta_menor')
            ->where('horizonts.process_id', '=', $this->process_id)
            ->get();




        $hydroConfig = Areas::join('hidro_configs', 'hidro_configs.area_id', 'areas.id')
            ->leftJoin('hidro_configs as hydro_tdownstream', 'hidro_configs.t_downstream_id', 'hydro_tdownstream.id')
            ->leftJoin('hidro_configs as hydro_sdownstream', 'hidro_configs.s_downstream_id', 'hydro_sdownstream.id')
            ->join('entrance_stages', 'entrance_stages.id', 'hidro_configs.entrance_stage_id')
            ->join('types', 'types.id', '=', 'hidro_configs.type_id')

            ->leftJoin('horizonts as entranceStageDate', 'entranceStageDate.id', '=', 'hidro_configs.entrance_stage_date')


            ->join('horizonts', 'horizonts.id', '=', 'hidro_configs.initial_Storage_stage')
            ->select('areas.name as areaName','hidro_configs.*', 'entrance_stages.name as entranceName', 'types.name as typeName', 'hydro_tdownstream.planta as t_downstream', 'hydro_sdownstream.planta as s_downstream', 'entrance_stages.name as entranceName', 'horizonts.period' ,'entranceStageDate.period as entranceStagePeriod')
            ->where('hidro_configs.process_id', '=', $this->process_id)
            ->get();





        //hydroExpansion
            // join con hydroConfig

        $HydroExpn = Horizont::join('hidro_expns', 'hidro_expns.horizont_id', 'horizonts.id')
            ->join('hidro_configs', 'hidro_configs.id', '=', 'hidro_expns.hidro_config_id')
            ->select('hidro_expns.*', 'horizonts.period', 'hidro_configs.planta')
            ->where('horizonts.process_id', '=', $this->process_id)
            ->get();


        // Inflow
            // join ocn escenarios
            // con horizontes
            // hydroConfig

        $Inflow = Horizont::join('inflows', 'inflows.horizont_id', 'horizonts.id')
            ->join('scenarios', 'scenarios.id', '=', 'inflows.scenario_id')
            ->join('hidro_configs', 'hidro_configs.id', '=', 'inflows.hidro_config_id')            
            ->select('inflows.*', 'horizonts.period', 'hidro_configs.planta', 'scenarios.name')
            ->where('horizonts.process_id', '=', $this->process_id)
            ->get();


        // WindConfig
            // join con entrance stage
            // horizonte
            // area

        $windConfig = Areas::join('wind_configs', 'wind_configs.area_id', 'areas.id')
            ->join('entrance_stages', 'entrance_stages.id', 'wind_configs.entrance_stage_id')
            ->join('horizonts', 'horizonts.id', '=', 'wind_configs.initial_storage_stage')
            ->select('wind_configs.*', 'entrance_stages.name as entranceName','areas.name as areaName', 'horizonts.period')
            ->where('wind_configs.process_id', '=', $this->process_id)
            ->get();


        // WindExpn
            // join con windConfig
        $WindExpn = Horizont::join('wind_expns', 'wind_expns.horizont_id', 'horizonts.id')
            ->join('wind_configs', 'wind_configs.id', '=', 'wind_expns.wind_config_id')
            ->select('wind_expns.*', 'horizonts.period', 'wind_configs.planta')
            ->where('horizonts.process_id', '=', $this->process_id)
            ->get();


        //SpeedIndices
            // join con meses y bloques y windConfig
        $SpeedIndices = Month::join('speed_indices', 'speed_indices.month_id', 'months.id')
            ->join('blocks', 'blocks.id', '=', 'speed_indices.block_id')
            ->join('wind_configs', 'wind_configs.id', '=', 'speed_indices.wind_config_id')            
            ->select('speed_indices.*', 'months.name', 'wind_configs.planta', 'blocks.name as blockName')
            ->where('wind_configs.process_id', '=', $this->process_id)
            ->get();


        // InflowWind
            // join con horizonte, scenario, windConfig

        $InflowWind = Horizont::join('inflow_winds', 'inflow_winds.horizont_id', 'horizonts.id')
            ->join('scenarios', 'scenarios.id', '=', 'inflow_winds.scenario_id')
            ->join('wind_configs', 'wind_configs.id', '=', 'inflow_winds.wind_config_id')            
            ->select('inflow_winds.*', 'horizonts.period', 'wind_configs.planta', 'scenarios.name')
            ->where('horizonts.process_id', '=', $this->process_id)
            ->get();


        // windM2Config
            // join con entranceStage, areas

        $windM2Config = Areas::join('wind_m2_configs', 'wind_m2_configs.area_id', 'areas.id')
            ->join('entrance_stages', 'entrance_stages.id', 'wind_m2_configs.entrance_stage_id')
            ->select('wind_m2_configs.*', 'entrance_stages.name as entranceName', 'areas.name as areaName')
            ->where('wind_m2_configs.process_id', '=', $this->process_id)
            ->get();



        // // InflowWindM2 (Acuerdate)

        // $InflowWindM2 = Horizont::join('inflow_wind_m2s', 'inflow_wind_m2s.horizont_id', 'horizonts.id')
        //     ->join('scenarios', 'scenarios.id', '=', 'inflow_wind_m2s.scenario')
        //     ->join('wind_m2_configs', 'wind_m2_configs.id', '=', 'inflow_wind_m2s.hidro_config_id')            
        //     ->select('inflow_wind_m2s.*', 'horizonts.period', 'wind_m2_configs.nombre_planta', 'scenarios.name')
        //     ->where('horizonts.process_id', '=', $this->process_id)
        //     ->get();


        // WPowCurveM2

        $WPowCurveM2 = WPowCurveM2::join('wind_m2_configs','wind_m2_configs.id', '=', 'w_pow_curve_m2s.wind_m2_config_id')

            ->where('w_pow_curve_m2s.process_id', '=', $this->process_id)
            ->get();


        // SpeedIndicesM2
        $SpeedIndicesM2 = Month::join('speed_indices_m2s', 'speed_indices_m2s.month_id', 'months.id')
            ->join('blocks', 'blocks.id', '=', 'speed_indices_m2s.block_id')
            ->join('wind_m2_configs', 'wind_m2_configs.id', '=', 'speed_indices_m2s.wind_config_id')            
            ->select('speed_indices_m2s.*', 'months.name', 'wind_m2_configs.nombre_planta', 'blocks.name as blockName')
            ->where('wind_m2_configs.process_id', '=', $this->process_id)
            ->get();


        // StorageConfig

        $storageConfig = Areas::join('storage_configs', 'storage_configs.area_id', 'areas.id')
            ->join('entrance_stages', 'entrance_stages.id', 'storage_configs.entrance_stage_id')
           ->join('horizonts', 'horizonts.id', '=', 'storage_configs.entrance_stage_date')
            ->select('storage_configs.*', 'entrance_stages.name as entranceName', 'areas.name as areaName')


            ->where('storage_configs.process_id', '=', $this->process_id)
            ->get();


        // StorageExpn

        $storageExpn = Horizont::join('storage_expansions', 'storage_expansions.horizont_id', 'horizonts.id')
            ->join('storage_configs', 'storage_configs.id', '=', 'storage_expansions.storage_config_id')
            ->select('storage_expansions.*', 'horizonts.period', 'storage_configs.name')
            ->where('horizonts.process_id', '=', $this->process_id)
            ->get();


        // Lines
        $linesConfig = Lines::leftJoin('areas as a_initial', 'lines.a_initial', 'a_initial.id')
            ->leftJoin('areas as b_final', 'lines.b_final', 'b_final.id')
            ->select('b_final.name as bFinal', 'a_initial.name as aInitial', 'lines.*')
            ->where('lines.process_id', '=', $this->process_id)
            ->get();



        // LinesExpn
        $linesExpn = linesExpansion::join('areas as a_initial', 'lines_expansions.a_initial', 'a_initial.id')
            ->join('horizonts', 'horizonts.id', 'lines_expansions.stage')
            ->leftJoin('areas as a_final', 'lines_expansions.a_final', 'a_final.id')
            ->select('a_final.name as aFinal', 'a_initial.name as aInitial', 'lines_expansions.*','horizonts.period')
            ->where('lines_expansions.process_id', '=', $this->process_id)
            ->get();


        // Demand
        $demands = Horizont::join('demands', 'demands.horizont_id', 'horizonts.id')
            ->join('areas', 'areas.id', '=', 'demands.area_id')
            ->select('areas.name as areaName', 'horizonts.period', 'demands.*')
            ->get();


        // Blocks
        // accedemos a las areas (no hace falta hacer join)
        $blocks = Blocks::where('process_id', '=', $this->process_id)->get();

        /*----------  AREAS  ----------*/
        
        $data['areas'] = [];

        foreach ($areas as $key => $area) {
            array_push($data['areas'], [$area->id, $area->name]);
        }

        array_unshift($data['areas'],['', 'Name']);



        /*----------  BLOQUES  ----------*/
        
        $data['blocks'] = [];

        foreach ($blocks as $key => $block) {
            array_push($data['blocks'], [$key + 1, $block->duration, $block->participation, ($block->storage_restrictions) ? 1 : 0]);
        }



        array_unshift($data['blocks'],["Block","Duration(%)","Participacion(%)","Storage_restrictions"]);


        /*----------  Demand  ----------*/
        // Demands esta dificil


        /*----------  ThermalConfig  ----------*/
        $data['thermal_config'] = [];

        $columnsThermalConfig = ["", "Capacity (MW)", "Entrance_Stage", "Tipo", "Area", "Fuel", "dummy", "GenMin", "GenMax", "forced_unavailability ", "historic_unavailability ", "O&M variable", "MBTU/MWh", "FE Ton/Mwh"];


        array_push($data["thermal_config"], $columnsThermalConfig);


        foreach ($thermalConfig as $thermalConf) {
            
            $row = [
                $thermalConf->name, 
                $thermalConf->capacity,
                $thermalConf->entranceName,
                $thermalConf->typeName,
                $thermalConf->areaName,
                $thermalConf->fuelName,
                '',
                $thermalConf->gen_min,
                $thermalConf->gen_max,
                $thermalConf->forced_unavailability,
                $thermalConf->historic_unavailability,
                $thermalConf['O&MVariable'],
                $thermalConf->heat_rate,
                $thermalConf->emision
        ];


        if ($thermalConf->entranceName !== "NE" && $thermalConf->entranceName !== "E") {
            $row[2] =  \Carbon\Carbon::parse($thermalConf->period)->format('Y-m-d h:i:s');
        }

            array_push($data['thermal_config'], $row);
        }


        /*----------  ThermalConfigExpansion  ----------*/
        $data['thermal_config_expn'] = [];

        $columnsThermalExpansion = ["","Modification","max (MW)","GenMin","GenMax","forced_unavailability","historic_unavailability"];


        array_push($data["thermal_config_expn"], $columnsThermalExpansion);


        foreach ($thermalExpn as $thermalExp) {
            $row = [
                $thermalExp->name, 
                \Carbon\Carbon::parse($thermalExp->period)->format('Y-m-d h:i:s'),
                $thermalExp->max,
                $thermalExp->gen_min,
                $thermalExp->gen_max,
                $thermalExp->forced_unavailability,
                $thermalExp->historic_unavailability
        ];


            array_push($data['thermal_config_expn'], $row);
        }





       /*----------  SmallConfig  ----------*/
        $data['small_config'] = [];

        $columnsSmallConfig = ["" ,"max (MW)" ,"Entrance_Stage"  ,"Tipo"    ,"Area"    ,"GenMin"  ,"GenMax"  ,"forced_unavailability", "historic_unavailability"];


        array_push($data["small_config"], $columnsSmallConfig);


        foreach ($smallConfig as $smallConf) {
            
            $row = [
                $smallConf->planta_menor, 
                $smallConf->max,
                $smallConf->entranceName,
                $smallConf->typeName,
                $smallConf->areaName,
                $smallConf->gen_min,
                $smallConf->gen_max,
                $smallConf->forced_unavailability,
                $smallConf->historic_unavailability
        ];


        if ($smallConf->entranceName !== "NE" && $smallConf->entranceName !== "E") {
            $row[2] =  \Carbon\Carbon::parse($smallConf->period)->format('Y-m-d h:i:s');
        }

            array_push($data['small_config'], $row);
        }

        /*----------  smallExpansion  ----------*/
        $data['small_expn'] = [];

        $columnsSmallExpansion = ["","Modification","max (MW)", "forced_unavailability","   historic_unavailability"];

        array_push($data["small_expn"], $columnsSmallExpansion);

        foreach ($SmallExpn as $smallEx) {
            
            $row = [
                $smallEx->planta_menor, 
                $smallEx->period->format('Y-m-d h:i:s'),
                $smallEx->max,
                $smallEx->forced_unavailability,
                $smallEx->historic_unavailability
        ];

            array_push($data['small_expn'], $row);
        }


       /*----------  Hydro_config  ----------*/
        $data['hydro_config'] = [];

        $columnsHydroConfig = ["","initial storage (Hm3)","min storage (Hm3)","max storage (Hm3)",   "capacity (MW)","prod coefficient (MW/m3/s)",  "max turbining outflow (m3/s)","Entrance_Stage","Initial_Storage_Stage","O&M cost ($/MWh)","T-Downstream","S-Downstream","Area","Tipo","forced_unavailability","historic_unavailability", "FE Ton/Mwh"];






        array_push($data["hydro_config"], $columnsHydroConfig);

        foreach ($hydroConfig as $hydro) {
          
            foreach ($horizonts as $key => $horizont) {
                if ($horizont->id === $hydro->initial_storage_stage) {
                    $hydro->initial_storage_stage = $key + 1;
                }
            }

            $row = [
                $hydro->planta, 
                $hydro->initial_storage,
                $hydro->min_storage,
                $hydro->max_storage,
                $hydro->capacity,
                $hydro->prod_coefficient,
                $hydro->max_turbining_outflow,
                $hydro->entranceName,
                $hydro->initial_storage_stage,
                $hydro['O&M'],
                $hydro->t_downstream,
                $hydro->s_downstream,
                $hydro->areaName,
                $hydro->typeName,
                $hydro->forced_unavailability,
                $hydro->historic_unavailability,
                $hydro->emision
        ];


        if ($hydro->entranceName !== "NE" && $hydro->entranceName !== "E") {
            $row[7] = \Carbon\Carbon::parse($hydro->entranceStagePeriod)->format('Y-m-d h:i:s');
        }

            array_push($data['hydro_config'], $row);
        }


        /*----------  HydroExpansion  ----------*/
        $data['hydro_expn'] = [];

        $columnsHydroExpansion = ["",  "capacity (MW)",   "prod coefficient (MW/m3/s)","max turbining outflow (m3/s)",    "Modification", "Code", "forced_unavailability","historic_unavailability(%) ", "max storage (Hm3)"];


        array_push($data["hydro_expn"], $columnsHydroExpansion);

        foreach ($HydroExpn as $hydro) {
            
            $row = [
                $hydro->planta,
                $hydro->capacity,
                $hydro->prod_coefficient,
                $hydro->max_turbing_outflow,
                $hydro->period->format('Y-m-d h:i:s'),
                "",
                $hydro->forced_unavailability,
                $hydro->historic_unavailability,
                $hydro->max_storage,
        ];
            array_push($data['hydro_expn'], $row);
        }



       /*----------  Wind_config  ----------*/
        $data['wind_config'] = [];

        $columnsWindConfig = ["","capacity (MW)","Losses","density(Kg/m3)","efficiency","diameter(m)","Speed_rated","Entrance_Stage","Initial_Storage_Stage","Area","forced_unavailability","Variability (%)","Speed_in","Spped_out","Betz_limit"];

        array_push($data["wind_config"], $columnsWindConfig);

        foreach ($windConfig as $wind) {

            foreach ($horizonts as $key => $horizont) {
                if ($horizont->id === $wind->initial_storage_stage) {
                    $wind->initial_storage_stage = $key + 1;
                }
            }

            $row = [
                $wind->planta, 
                $wind->capacity,
                $wind->losses,
                $wind->density,
                $wind->efficiency,
                $wind->diameter,
                $wind->speed_rated,
                $wind->entranceName,
                $wind->initial_storage_stage,
                $wind->areaName,
                $wind->forced_unavailability,
                $wind->variability,
                $wind->speed_in,
                $wind->speed_out,
                $wind->betz_limit
        ];


        if ($wind->entranceName !== "NE" && $wind->entranceName !== "E") {
            $row[7] = \Carbon\Carbon::parse($wind->entrance_stage_date)->format('Y-m-d h:i:s');
        }


            array_push($data['wind_config'], $row);
        }



        /*----------  WindExpansion  ----------*/
        $data['wind_expn'] = [];

        $columnsWindExpansion = ["","Modification","capacity (MW)","efficiency","number_turbines","forced_unavailability","historic_unavailability  (%)", "Losses"];


        array_push($data["wind_expn"], $columnsWindExpansion);

        foreach ($WindExpn as $wind) {
            
            $row = [
                $wind->planta,
                $wind->period->format('Y-m-d h:i:s'),
                $wind->capacity,
                $wind->efficiency,
                $wind->number_turbines,
                $wind->forced_unavailability,
                $wind->historic_unavailability,
                $wind->losses,
        ];


            array_push($data['wind_expn'], $row);
        }





       /*----------  WindM2_config  ----------*/
        $data['windM2Config'] = [];

        $columnsWindM2Config = ["","capacity (MW)","Losses","wSpeed_min","wSpeed_max","speed_resolution","Measuring height","hub height","adjustment","density(Kg/m3)","Distance(m)","diameter(m)","Entrance_Stage","Area","SpeedDataMinutes","rows"];

        array_push($data["windM2Config"], $columnsWindM2Config);

        foreach ($windM2Config as $wind) {
            
            $row = [
                $wind->nombre_planta, 
                $wind->capacity,
                $wind->losses,
                $wind->wSpeed_min,
                $wind->wSpeed_max,
                $wind->speed_resolution,
                $wind->measuring_height,
                $wind->hub_height,
                $wind->adjustment,
                $wind->density,
                $wind->diameter,
                $wind->entranceName,
                $wind->areaName,
                $wind->speedDataMinutes,
                ''
        ];


        if ($wind->entranceName !== "NE" && $wind->entranceName !== "E") {
            $row[11] = $wind->entrance_stage_date;
        }

            array_push($data['windM2Config'], $row);
        }







       /*----------  StorageConfig  ----------*/
        $data['storage_config'] = [];

        $columnsStorageConfig = ["","initial storage (pu)","min storage (pu)","max storage (pu)","capacity (Mwh)","Efficiency","max outflow (MWh/s)","Entrance_Stage","Linked","Portfolio","Area","forced_unavailability","historic_unavailability(%)","power rate"];


        array_push($data["storage_config"], $columnsStorageConfig);


        foreach ($storageConfig as $storage) {
            
            $row = [
                $storage->name, 
                $storage->initial_storage,
                $storage->min_storage,
                $storage->max_storage,
                $storage->capacity,
                $storage->efficiency,
                $storage->max_outflow,
                $storage->entranceName,
                $storage->linked,
                $storage->portfolio,
                $storage->areaName,
                $storage->forced_unavailability,
                $storage->historic_unavailability,
                $storage->power_rate
        ];

        if ($storage->entranceName !== "NE" && $storage->entranceName !== "E") {
            $row[7] =  \Carbon\Carbon::parse($storage->period)->format('Y-m-d h:i:s');
        }

            array_push($data['storage_config'], $row);
        }


       /*----------  StorageExpansion  ----------*/
        $data['storage_expansion'] = [];

        $columnsStorageConfig = ["","Modification","capacity (MW)","Efficiency","max outflow (MWh/s)", "forced_unavailability", "historic_unavailability  (%)"];


        array_push($data["storage_expansion"], $columnsStorageConfig);


        foreach ($storageExpn as $storage) {
            
            $row = [
                $storage->name, 
                \Carbon\Carbon::parse($storage->period)->format('Y-m-d h:i:s'),
                $storage->capacity,
                $storage->efficiency,
                $storage->max_outflow,
                $storage->forced_unavailability,
                $storage->historic_unavailability
        ];

            array_push($data['storage_expansion'], $row);
        }



       /*----------  Lines  ----------*/
        $data['lines'] = [];

        $columnsLines = ["A_Initial","B_Final","A_to_B[MW]","B_to_Al[MW]","Efficiency","resistence","reactance"];


        array_push($data["lines"], $columnsLines);


        foreach ($linesConfig as $lines) {
            
            $row = [
                $lines->aInitial, 
                $lines->bFinal,
                $lines->a_to_b,
                $lines->b_to_a,
                $lines->efficiency,
                $lines->resistence,
                $lines->reactance
        ];


            array_push($data['lines'], $row);
        }

       /*----------  StorageExpansion  ----------*/
        $data['lines_expansion'] = [];

        $columnsLinesExpansion = ["A_Initial","A_Final","stage","A_to_B[MW]","B_to_Al[MW]","Efficiency","resistence","reactance"];


        array_push($data["lines_expansion"], $columnsLinesExpansion);


        foreach ($linesExpn as $lines) {
            
            $row = [
                $lines->aInitial,
                $lines->aFinal,
                \Carbon\Carbon::parse($storage->period)->format('Y-m-d h:i:s'),
                $lines->a_b,
                $lines->b_ai,
                $lines->efficiency,
                $lines->resistence,
                $lines->reactance
            ];

            array_push($data['lines_expansion'], $row);
        }



       /*----------  FlowGate  ----------*/
        $data['flowgate'] = [];
        $ColumnsflowGate = ["flowgate", "lines",   "flow_limit",  "stage_ini",   "stage_fin",   "NI_L1",   "NF_L1",   "NI_L2",   "NF_L2",   "NI_L3",   "NF_L3",   "NI_L4",   "NF_L4",   "NI_L5",   "NF_L5",   "NI_L6",   "NF_L6"];

        array_push($data["flowgate"], $ColumnsflowGate);


       /*----------  Fuel  ----------*/

    // recorremos todos los horizontes
    $fuels = FuelCostPlant::where('process_id', '=', $this->process_id)->get();

    $fuelsNames = ['USD/MBTU'];

    foreach ($fuels as $fuel) {
        array_push($fuelsNames, $fuel->name);
    }


    $data['fuel'] = [];
    foreach ($horizonts as $horizont) {
        $dateFuel = [\Carbon\Carbon::parse($horizont->period)->format('Y-m-d h:i:s')];
        foreach ($fuels as $fuel) {
            $val = FuelCostHorizont::where('planta_fuel_id', '=', $fuel->id)
                ->leftJoin('fuel_cost_plants', 'fuel_cost_plants.id', '=', 'fuel_cost_horizonts.planta_fuel_id')
                ->where('horizont_id', '=', $horizont->id)
                ->where('process_id', '=', $this->process_id)
                ->first();
            if ($val) {
                array_push($dateFuel, $val->value);
            } else {
                array_push($dateFuel, 0);
            }
        }

        array_push($data['fuel'], $dateFuel);
    }

    array_unshift($data['fuel'], $fuelsNames);


   
    /*---------- Demanda  ----------*/

    // recorremos todos los horizontes
    $areas = Areas::where('process_id', '=', $this->process_id)->get();
    $areasName = ['Month', 'National'];

    foreach ($areas as $area) {
        array_push($areasName, $area->name);
    }


    $data['demand'] = [];
    foreach ($horizonts as $horizont) {
        $dot = [\Carbon\Carbon::parse($horizont->period)->format('Y-m-d h:i:s'), $horizont->national];
        foreach ($areas as $area) {
            $val = Demand::where('area_id', '=', $area->id)
                ->where('horizont_id', '=', $horizont->id)
                ->where('process_id', '=', $this->process_id)
                ->first();
            if ($val) {
                array_push($dot, $val->factor);
            } else {
                array_push($dot, 0);
            }
        }

        array_push($data['demand'], $dot);
    }

    array_unshift($data['demand'], $areasName);


    /*---------- Costos de racionamiento  ----------*/

    // recorremos todos los horizontes
    $segments = Segment::where('process_id', '=', $this->process_id)->get();


    $segsNames = [''];

    foreach ($segments as $segment) {
        array_push($segsNames, $segment->name); 
    }


    $data['rationingCost'] = [];
    foreach ($horizonts as $horizont) {
        $dot = [\Carbon\Carbon::parse($horizont->period)->format('Y-m-d h:i:s')];
        foreach ($segments as $seg) {
            $val = RationingCost::where('segment_id', '=', $seg->id)
                ->where('horizont_id', '=', $horizont->id)
                ->where('process_id', '=', $this->process_id)
                ->first();
            if ($val) {
                array_push($dot, $val->value);
            } else {
                array_push($dot, 0);
            }
        }

        array_push($data['rationingCost'], $dot);
    }

    array_unshift($data['rationingCost'], $segsNames);


    // buscamos la posicion en la que esta actualmente

    $keyPositionRationingSelected = null;

    foreach ($data['rationingCost'] as &$rationing) {

        $valSelected = RationingCost::where('segment_id', '=', $this->data->segment_id)
                    ->join('segments', 'segments.id', '=', 'segment_id' )
                    ->where('horizont_id', '=', $horizont->id)
                    ->where('segments.process_id', '=', $this->process_id)
                    ->first();


        if ($valSelected) {
            foreach ($rationing as $keyPositionRationing => $positionRationing) {
                if ($valSelected->name === $positionRationing) {
                  $keyPositionRationingSelected = $keyPositionRationing;

                }
            }
            $this->moveElement($rationing,$keyPositionRationingSelected, 1);
        }


    }


    /*---------- Inflow  ----------*/

    // recorremos todos los hydro que sean del proceso
    $hydro = HidroConfig::where('process_id', '=', $this->process_id)->get();
    // obtenemos las columnas de la tabla
    $columnsHydro = ['stage', 'scenario'];

    foreach ($hydro as $value) {
        array_push($columnsHydro, $value->planta);
    }

    $data['inflow'] = [];
    // recorremos horizontes
    foreach ($horizonts as $key => $horizont) {
       foreach ($scenarios as $scenarioKey => $scenario) {
            $valuesHydro = [$key + 1, $scenarioKey + 1];
            foreach ($hydro as $fop => $plant) {
                   $inf = Inflow::where('process_id', '=', $this->process_id)
                            ->where('horizont_id', '=', $horizont->id)
                            ->where('scenario_id', '=', $scenario->id)
                            ->where('hidro_config_id', '=', $plant->id)
                            ->first();

                    if($inf) {
                        array_push($valuesHydro, $inf->value);
                    } else {
                        array_push($valuesHydro, 0);                        
                    }
            }

            array_push($data['inflow'], $valuesHydro);



       }
    }
    
    array_unshift($data['inflow'], $columnsHydro);

    /*---------- SpeedIndices  ----------*/

    // recorremos todos los hydro que sean del proceso
    $winds = WindConfig::where('process_id', '=', $this->process_id)->get();
    // obtenemos las columnas de la tabla
    $columnsSpeed = ['Month', 'Block'];

    foreach ($winds as $wind) {
        array_push($columnsSpeed, $wind->planta);
    }


    $data['speedIndices'] = [];
    // recorremos horizontes
    foreach ($months as $key => $month) {
       foreach ($blocks as $keyBlock => $block) {
            $valuesSpeed = [$month->id, $keyBlock + 1];
            foreach ($winds as $fop => $plant) {
                   $inf = SpeedIndices::where('process_id', '=', $this->process_id)
                            ->where('month_id', '=', $month->id)
                            ->where('block_id', '=', $block->id)
                            ->where('wind_config_id', '=', $plant->id)
                            ->first();

                    if($inf) {
                        array_push($valuesSpeed, $inf->value);
                    } else {
                        array_push($valuesSpeed, 0);                        
                    }
            }

            array_push($data['speedIndices'], $valuesSpeed);
       }
    }
    
    array_unshift($data['speedIndices'], $columnsSpeed);




    /*---------- Inflow Wind ----------*/

    // recorremos todos los hydro que sean del proceso
    $hydro = WindConfig::where('process_id', '=', $this->process_id)->get();
    // obtenemos las columnas de la tabla
    $columnsInflowWind = ['stage', 'scenario'];

    foreach ($hydro as $value) {
        array_push($columnsInflowWind, $value->planta);
    }

    $data['inflowWind'] = [];
    // recorremos horizontes
    foreach ($horizonts as $key => $horizont) {
       foreach ($scenarios as $scenarioKey => $scenario) {
            $valuesInflowWind = [$key + 1, $scenarioKey + 1];
            foreach ($hydro as $fop => $plant) {
                   $inf = Inflow::where('process_id', '=', $this->process_id)
                            ->where('horizont_id', '=', $horizont->id)
                            ->where('scenario_id', '=', $scenario->id)
                            ->where('hidro_config_id', '=', $plant->id)
                            ->first();

                    if($inf) {
                        array_push($valuesInflowWind, $inf->value);
                    } else {
                        array_push($valuesInflowWind, 0);                        
                    }
            }

            array_push($data['inflowWind'], $valuesInflowWind);

       }
    }
    
    array_unshift($data['inflowWind'], $columnsInflowWind);



    /*---------- Inflow Wind m2 ----------*/

    // recorremos todos los hydro que sean del proceso
    $wind = WindM2Config::where('process_id', '=', $this->process_id)->get();
    // obtenemos las columnas de la tabla
    $columnsInflowWindM2 = ['stage', 'scenario'];

    foreach ($wind as $value) {
        array_push($columnsInflowWindM2, $value->nombre_planta);
    }

    //scenarios
    $scenarios =  Scenario::where('scenarios.process_id', '=', $this->process_id)->get();
    $horizonts = Horizont::where('horizonts.process_id', '=', $this->process_id)->get();


    $data['inflowWindM2'] = [];
    // recorremos horizontes
    foreach ($horizonts as $key => $horizont) {
       foreach ($scenarios as $keyScenario =>  $scenario) {
            $valuesInflowWindM2 = [$key + 1, $keyScenario];
            foreach ($wind as $fop => $plant) {


                   $inf = inflowWindM2::where('process_id', '=', $this->process_id)
                            ->where('horizont_id', '=', $horizont->id)
                            ->where('scenario', '=', $scenario->id)
                            ->where('wind_config_id', '=', $plant->id)
                            ->first();

                    if($inf) {
                        array_push($valuesInflowWindM2, $inf->value);
                    } else {
                        array_push($valuesInflowWindM2, 0);                        
                    }
            }

            array_push($data['inflowWindM2'], $valuesInflowWindM2);

       }
    }
    
    array_unshift($data['inflowWindM2'], $columnsInflowWindM2);




    /*---------- SpeedIndicesM2  ----------*/

    // recorremos todos los hydro que sean del proceso
    $winds = WindM2Config::where('process_id', '=', $this->process_id)->get();
    // obtenemos las columnas de la tabla
    $columnsSpeed = ['Month', 'Block'];

    foreach ($winds as $wind) {
        array_push($columnsSpeed, $wind->nombre_planta);
    }


    $data['speedIndicesM2'] = [];
    // recorremos horizontes
    foreach ($months as $key => $month) {
       foreach ($blocks as $blockKey => $block) {
            $valuesSpeed = [$month->id, $blockKey + 1];
            foreach ($winds as $fop => $plant) {
                   $inf = SpeedIndicesM2::where('process_id', '=', $this->process_id)
                            ->where('month_id', '=', $month->id)
                            ->where('block_id', '=', $block->id)
                            ->where('wind_config_id', '=', $plant->id)
                            ->first();

                    if($inf) {
                        array_push($valuesSpeed, $inf->value);
                    } else {
                        array_push($valuesSpeed, 0);                        
                    }
            }

            array_push($data['speedIndicesM2'], $valuesSpeed);
       }
    }
    
    array_unshift($data['speedIndicesM2'], $columnsSpeed);


    /*---------- WPOWCurveM2  ----------*/
    $data['WPowCurve'] = [];
    $winds = WindM2Config::where('process_id', '=', $this->process_id)->get();
    // obtenemos las columnas de la tabla
    $columnsWPowCurveM2 = [];


    $WPowData = [];

    foreach ($winds as $key => $wind) {
        array_push($columnsWPowCurveM2,
            'P:'.$wind->nombre_planta,
            'CT:'.$wind->nombre_planta,
            'TpR:'.$wind->nombre_planta
        );


        $WPowData[$key] = WPowCurveM2::where('process_id', '=', $this->process_id)
            ->where('wind_m2_config_id', '=', $wind->id)
            ->get();

    }


    $det = [];
    foreach ($WPowData as $key => $rDat) {
        foreach ($rDat as $k => $value) {
            if (!isset($det[$k])) {
                $det[$k] = [];
            }
            array_push($det[$k], $value->p, $value->CT, $value->TpR);
        }
    }

    $data['WPowCurve'] = $det;

    array_unshift($data['WPowCurve'], $columnsWPowCurveM2);




    /*
    * En el caso de que se hayan generado datos de viento
    * debemos abrir los CSV 
    */ 
    if ($this->data->generate_wind) {
        $data['inflow'] = [];
        $inflowR = \Excel::load(storage_path() .'/app/models/' . $this->data->userId . '/' . $this->data->id . '/rscript/test/Forecast_data/inflow.csv')->get();


        $valuesInflowR = [''];
        $columnsInflowR = [];
        foreach ($inflowR as $inf) {
            $r = [];
            $columnsInflowRInternal = [];
            foreach ($inf as $column => $v) {
                array_push($columnsInflowRInternal, $column);
                array_push($r, $v);
                
            }
            $columnsInflowR = $columnsInflowRInternal;
            array_push($valuesInflowR, $r);
        }
        
        array_shift($valuesInflowR);
        array_unshift($valuesInflowR, $columnsInflowR);
        $data['inflow'] = array($valuesInflowR)[0];


        $data['inflowWind'] = [];
        $inflowR = \Excel::load(storage_path() .'/app/models/' . $this->data->userId . '/' . $this->data->id . '/rscript/test/Forecast_data/inflow.csv')->get();


        $valuesInflowWindR = [''];
        $columnsInflowWindR = [];
        foreach ($inflowR as $inf) {
            $r = [];
            $columnsInflowWindRInternal = [];
            foreach ($inf as $column => $v) {
                array_push($columnsInflowWindRInternal, $column);
                array_push($r, $v);
                
            }
            $columnsInflowWindR = $columnsInflowWindRInternal;
            array_push($valuesInflowWindR, $r);
        }
        
        array_shift($valuesInflowWindR);
        array_unshift($valuesInflowWindR, $columnsInflowWindR);
        $data['inflowWind'] = array($valuesInflowWindR)[0];



    }



        /*----------  Generación del archivo Excel  ----------*/

        \Excel::create($this->process_id, function($excel) use ($data) {
             
             $excel->sheet('Areas', function($sheet) use ($data) {
                $sheet->fromArray($data['areas'], null, 'A1', true, false);
             });

             $excel->sheet('Thermal_config', function($sheet) use ($data) {
                $sheet->fromArray($data['thermal_config'], null, 'A1', true, false);
             });


             $excel->sheet('Thermal_expn', function($sheet) use ($data) {
                $sheet->fromArray($data['thermal_config_expn'], null, 'A1', true, false);
             });

             $excel->sheet('FuelCost', function($sheet) use ($data) {
                $sheet->fromArray($data['fuel'], null, 'A1', true, false);
             });

             $excel->sheet('RationingCosts', function($sheet) use ($data) {
                $sheet->fromArray($data['rationingCost'], null, 'A1', true, false);
             });

             $excel->sheet('Small_config', function($sheet) use ($data) {
                $sheet->fromArray($data['small_config'], null, 'A1', true, false);
             });


             $excel->sheet('Small_expn', function($sheet) use ($data) {
                $sheet->fromArray($data['small_expn'], null, 'A1', true, false);
             });

             $excel->sheet('Hydro_config', function($sheet) use ($data) {
                $sheet->fromArray($data['hydro_config'], null, 'A1', true, false);
             });


             $excel->sheet('Hydro_expn', function($sheet) use ($data) {
                $sheet->fromArray($data['hydro_expn'], null, 'A1', true, false);
             });


             $excel->sheet('Inflow', function($sheet) use ($data) {
                $sheet->fromArray($data['inflow'], null, 'A1', true, false);
             });

             $excel->sheet('Wind_config', function($sheet) use ($data) {
                $sheet->fromArray($data['wind_config'], null, 'A1', true, false);
             });


             $excel->sheet('Wind_expn', function($sheet) use ($data) {
                $sheet->fromArray($data['wind_expn'], null, 'A1', true, false);
             });

             $excel->sheet('SpeedIndices', function($sheet) use ($data) {
                $sheet->fromArray($data['speedIndices'], null, 'A1', true, false);
             });

             $excel->sheet('InflowWind', function($sheet) use ($data) {
                $sheet->fromArray($data['inflowWind'], null, 'A1', true, false);
             });

             $excel->sheet('Wind_M2_config', function($sheet) use ($data) {
                $sheet->fromArray($data['windM2Config'], null, 'A1', true, false);
             });

             $excel->sheet('InflowWind_M2', function($sheet) use ($data) {
                $sheet->fromArray($data['inflowWind'], null, 'A1', true, false);
             });

             $excel->sheet('WPowCurve_M2', function($sheet) use ($data) {
                $sheet->fromArray($data['WPowCurve'], null, 'A1', true, false);
             });

             $excel->sheet('SpeedIndices_M2', function($sheet) use ($data) {
                $sheet->fromArray($data['speedIndicesM2'], null, 'A1', true, false);
             });

             $excel->sheet('Storage_config', function($sheet) use ($data) {
                $sheet->fromArray($data['storage_config'], null, 'A1', true, false);
             });

             $excel->sheet('Storage_expn', function($sheet) use ($data) {
                $sheet->fromArray($data['storage_expansion'], null, 'A1', true, false);
             });

             $excel->sheet('Lines', function($sheet) use ($data) {
                $sheet->fromArray($data['lines'], null, 'A1', true, false);
             });

             $excel->sheet('Lines_expn', function($sheet) use ($data) {
                $sheet->fromArray($data['lines_expansion'], null, 'A1', true, false);
             });


             $excel->sheet('FlowGates', function($sheet) use ($data) {
                $sheet->fromArray($data['flowgate'], null, 'A1', true, false);
             });


             $excel->sheet('Demand', function($sheet) use ($data) {
                $sheet->fromArray($data['demand'], null, 'A1', true, false);
             });

             $excel->sheet('Blocks', function($sheet) use ($data) {
                $sheet->fromArray($data['blocks'], null, 'A1', true, false);
             });
                
//->export('xlsx')
        })->store('xlsx', storage_path() .'/app/models/' . $this->data->userId . '/' . $this->process_id . '/python/datasystem/');

    }
}
