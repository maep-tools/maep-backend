<?php namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateprocessAPIRequest;
use App\Http\Requests\API\UpdateprocessAPIRequest;
use App\Models\process;
use App\Repositories\processRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\UserMeta;
use DB;
use App\Models\User;
use Log;
use App\Jobs\ProcessPythonScript;
use App\Jobs\GenerateExcel;
use App\Jobs\PrepareCalculator;
use App\Jobs\ProcessRScript;
use App\Models\Areas;
use App\Models\Scenario;
use App\Models\Demand;
use App\Models\Horizont;
use App\Models\FuelCostHorizont;
use App\Models\FuelCostPlant;
use App\Models\Segment;
use App\Models\RationingCost;
use App\Models\Inflow;
use App\Models\InflowWind;
use App\Models\InflowWindM2;
use App\Models\HidroConfig;
use App\Models\WindConfig;
use App\Models\Month;
use App\Models\Blocks;
use App\Models\SmallConfig;
use App\Models\speedIndices;
use App\Models\WindM2Config;
use App\Models\SpeedIndicesM2;
use App\Models\ThermalConfig;
use App\Models\Thermal_expn;
use App\Models\Lines;
use App\Models\LinesExpansion;
use App\Models\storage_config;
use Illuminate\Filesystem\Filesystem;

/**
 * Class processController
 * @package App\Http\Controllers\API
 */

class processAPIController extends BaseController
{
    /** @var  processRepository */
    private $processRepository;

    protected $Filesystem;


    public function __construct(processRepository $processRepo)
    {
        $this->processRepository = $processRepo;
        $this->Filesystem = new Filesystem();             

    }

    public function sendResponse ($e) {
        return response()->json($e);
    }


    public function getTemplates () {
      return Process::where('template', '=', 1)->get();
    }

    public function uploadWind (Request $request, $id) {
       $hasFile = $request->hasFile('file');
       $file = $request->file('file');
       $file->move(storage_path("app/generate/wind/"), $id.".csv");

       $windModel = \App\Models\WindConfig::find($id);
       $windModel->uploaded_series = 1;
       $windModel->update($windModel->toArray());
    }


    public function uploadHidric (Request $request, $id) {
       $hasFile = $request->hasFile('file');
       $file = $request->file('file');
       $file->move(storage_path("app/generate/hydric/"), $id.".csv");

       $hydro = \App\Models\HidroConfig::find($id);
       $hydro->uploaded_series = 1;
       $hydro->update($hydro->toArray());
    }

    public function uploadExcel (Request $request, $id) {
     $hasFile = $request->hasFile('file');
     $file = $request->file('file');
     $file->move(storage_path("app/temporal/"), $id.".xlsx");
    }

    public function validateProcess($id) {

        $errors = [];
        $valid = true;

        $process = Process::where('id', '=', $id)->first();
        // obtenemos todas las areas
        $areasModel = Areas::where('process_id', '=', $id);
        $areas = $areasModel->get();
        $areasCount = $areasModel->count();

        $linesCount = Lines::where('process_id', '=', $id);
        
        if ($areasCount >= 2 && $linesCount === 0) {
          array_push($errors, "Si existen al menos dos areas deben existir lineas");
          $isValid = false;
        }


        // obtenemos todos los horizontes
        $horizonts = Horizont::where('process_id', '=', $id)->get();
        // contamos los horizontes
        $horizontCount = $horizonts->count();

        $nationalAcum = 0;
        foreach ($horizonts as $horizont) {
          $nationalAcum = $horizont->national + $nationalAcum;
        }

        // contamos la demanda
        $demandCounter = [];
        
        // recorremos todas las areas creadas
        foreach ($areas as $area) {
            // obtenemos todas las demandas asociadas a la demanda
            $demands = Demand::where('area_id', '=', $area->id)->get();
            // contamos la demanda asociada al area
            $demandsCount = $demands->count();
            // agregamos a la lista
            array_push($demandCounter, [
                'area_id' => $area->id,
                'area_name' => $area->name,
                'demand_count' => $demandsCount,
                'horizonts_count' => $horizontCount,
                'valid' => ($demandsCount === $horizontCount) && $horizontCount > 0
            ]);
        }


        $validDemand = true;
        foreach ($demandCounter as $demandRegister) {
              if (!$demandRegister['valid']) {
                $validDemand = false;
              }
        }

        if ($horizontCount < 1) {
          $validDemand = false;
        }

        $demandCounter = [
              'registers' => $demandCounter,
              'valid' => $validDemand
          ];

        // validamos que el factor de la demanda de las areas sea igual que el tamaño del horizonte
        // si alguno de los factores esta vacio entonces este debera ser cero.

        // obtiene los tipos de combustibles por proceso
        $fuelCostPlants = FuelCostPlant::where('process_id', '=', $id)->get();        
        $fuelCounter = [];
        foreach ($fuelCostPlants as $fuel) {
           // obtiene los fuelhorizonts por combustible
           $fuelHorizonts =  FuelCostHorizont::where('planta_fuel_id', '=', $fuel->id)->get();
           // cuenta los combustibles
           $fuelHorizontsCount = $fuelHorizonts->count();
           // agregar 
           array_push($fuelCounter, [
                'fuel_name' => $fuel->name,
                'fuel_id' => $fuel->id,
                'fuel_horizont_count' => $fuelHorizontsCount,
                'horizonts_count' => $horizontCount,
                'valid' => $fuelHorizontsCount === $horizontCount
           ]);
        }

        $validFuelCounter = true;
        foreach ($fuelCounter as $fuelRegister) {
              if (!$fuelRegister['valid']) {
                $validFuelCounter = false;
              }
        }

        if ($horizontCount < 1) {
          $validFuelCounter = false;
        }



        $fuelCounter = [
          'registers' => $fuelCounter,
          'valid' => $validFuelCounter
        ];

        // procedimiento para costos de racionamiento

        $segments = Segment::where('process_id', '=', $id)->get(); 
        $segmentsCounter = $segments->count();
        $rationingCounter = [];
        foreach ($segments as $segment) {

            $rationingCosts = RationingCost::where('segment_id', '=', $segment->id)->get();

            $acumRationing = 0;
            foreach ($rationingCosts as $cost) {
              $acumRationing = $acumRationing + $cost->value;
            }

            if ($acumRationing == 0) {
              array_push($errors, "Todos los valores de racionamiento asociados al segmento ". $segment->name . " no pueden ser 0.");
              $isValid = false;
            }


            $rationingCostsCount = $rationingCosts->count();

           array_push($rationingCounter, [
                'segment_name' => $segment->name,
                'segment_id' => $segment->id,
                'rationing_cost_count' => $rationingCostsCount,
                'horizonts_count' => $horizontCount,
                'valid' => $rationingCostsCount === $horizontCount
           ]);
        }


        $validRationing = true;
        foreach ($rationingCounter as $rationingRegister) {
              if (!$rationingRegister['valid']) {
                $validRationing = false;
              }
        }

        if ($horizontCount < 1) {
          $validRationing = false;
        }



        $rationingCounter = [
          'registers' => $rationingCounter,
          'valid' => $validRationing
        ];

        //realizamos el mismo procedimiento con afluentes hidricos


        $hydroConfig = HidroConfig::where('process_id', '=', $id)->get(); 
        $hydroConfigCounter = $hydroConfig->count();

        $inflowCounter = [];

        foreach ($hydroConfig as $hydro) {

          if ($process->generate_wind) {
              if (!$hydro->uploaded_series) {
                array_push($errors, "Es necesario agregar las series hídricas en la planta hydro " . $hydro->planta . ".");
                $isValid = false;                
              }
          }

          if ($hydro->min_storage > $hydro->max_storage) {
              array_push($errors, "El almacenamiento mínimo de la planta hydro " . $hydro->name . " no puede ser mayor que el almacenamiento máximo.");
              $isValid = false;
          }

          if ($hydro->initial_storage > 1) {
              array_push($errors, "El almacenamiento inicial de la planta hydro " . $hydro->name . " no puede ser mayor a 1.");
              $isValid = false;
          }

            $inflow = Inflow::where('hidro_config_id', '=', $hydro->id)->get();
            $inflowCount = $inflow->count();
            $totalScenario = Scenario::where('process_id', '=', $id)->count();
            $scenarioCounter = $horizontCount * $totalScenario;
            array_push($inflowCounter, [
                'hydro_name' => $hydro->planta,
                'hydro_id' => $hydro->id,
                'inflowCount' => $inflowCount,
                'horizonts_count' => $scenarioCounter,
                'valid' => $scenarioCounter === $inflowCount
           ]);     
        }


        $validInflowHydro = true;
        foreach ($inflowCounter as $inflowRegister) {
              if (!$inflowRegister['valid']) {
                $validInflowHydro = false;
              }
        }

        if ($horizontCount < 1) {
          $validInflowHydro = false;
        }



        $inflowCounter = [
          'registers' => $inflowCounter,
          'valid' => $validInflowHydro
        ];


        $windConfigs = WindConfig::where('process_id', '=', $id)->get(); 
        $windConfigCounter = $windConfigs->count();

        $inflowWindCounter = [];

        foreach ($windConfigs as $wind) {

          if ($process->generate_wind) {
              if (!$wind->uploaded_series) {
                array_push($errors, "Es necesario agregar las series eólicas en la planta eólica " . $wind->planta . ".");
                $isValid = false;                
              }
          }

            $inflowWind = InflowWind::where('wind_config_id', '=', $wind->id)->get();
            $inflowWindCount = $inflowWind->count();
            $totalScenario = Scenario::where('process_id', '=', $id)->count();
            $scenarioCounter = $horizontCount * $totalScenario;
            array_push($inflowWindCounter, [
                'wind_name' => $wind->planta,
                'wind_id' => $wind->id,
                'inflowCount' => $inflowWindCount,
                'horizonts_count' => $scenarioCounter,
                'valid' => $scenarioCounter === $inflowWindCount
           ]);     
        }

        $validInflowWind = true;
        foreach ($inflowWindCounter as $inflowWindRegister) {
              if (!$inflowWindRegister['valid']) {
                $validInflowWind = false;
              }
        }

        if ($horizontCount < 1) {
          $validInflowWind = false;
        }


        if ($process->generate_wind) {
          $validInflowWind = true;
        }



        $inflowWindCounter = [
          'registers' => $inflowWindCounter,
          'valid' => $validInflowWind
        ];


        $windConfigsM2 = WindM2Config::where('process_id', '=', $id)->get(); 
        $windConfigCounterM2 = $windConfigsM2->count();

        $inflowWindCounterM2 = [];

        foreach ($windConfigsM2 as $windM2) {
            $inflowWindM2 = InflowWindM2::where('wind_config_id', '=', $windM2->id)->get();
            $inflowWindM2Count = $inflowWindM2->count();
            $totalScenario = Scenario::where('process_id', '=', $id)->count();
            $scenarioCounter = $horizontCount * $totalScenario;
            array_push($inflowWindCounterM2, [
                'wind_name' => $windM2->nombre_planta,
                'wind_id' => $windM2->id,
                'inflowCount' => $inflowWindM2Count,
                'horizonts_count' => $scenarioCounter,
                'valid' => $scenarioCounter === $inflowWindM2Count
           ]);     
        }

        $validInflowWindM2 = true;
        foreach ($inflowWindCounterM2 as $inflowWindM2Register) {
              if (!$inflowWindM2Register['valid']) {
                $validInflowWindM2 = false;
              }
        }

        if ($horizontCount < 1) {
          $validInflowWindM2 = false;
        }

        if ($process->generate_wind) {
          $validInflowWindM2 = true;
        }


        $inflowWindCounterM2 = [
          'registers' => $inflowWindCounterM2,
          'valid' => $validInflowWindM2
        ];

        $blocksConfigs = Blocks::where('process_id', '=', $id)->get(); 
        
        $blocksCounter = $blocksConfigs->count();

        $acumBlockDuration = 0;
        foreach ($blocksConfigs as $block) {
          $acumBlockDuration = $acumBlockDuration + $block->duration;
        }

        $windConfigCounter = $windConfigs->count();

        $monthsCounter = 12;
        
        $speedIndicesCounter = [];

        foreach ($windConfigs as $windConfig) {
            $speedIndicesByWindConfig = speedIndices::where('wind_config_id', '=', $windConfig->id)->join('wind_configs', 'wind_configs.id', '=', 'speed_indices.wind_config_id')->get();
            $speedIndicesByWindConfigCounter = $speedIndicesByWindConfig->count();
            //dd($speedIndicesByWindConfigCounter);

            array_push($speedIndicesCounter, [
                'wind_config_name' => $windConfig->planta,
                'wind_config_id' => $windConfig->id,
                'speedIndicesByWindConfigCounter' => $speedIndicesByWindConfigCounter,
                'blocksCounter' => $blocksCounter,
                'valid' => ($blocksCounter * $monthsCounter) === $speedIndicesByWindConfigCounter
           ]);     
        }


        $validSpeedIndices = true;
        foreach ($speedIndicesCounter as $speedIndicesRegister) {
              if (!$speedIndicesRegister['valid']) {
                $validSpeedIndices = false;
              }
        }

        if ($blocksCounter < 1) {
          $validSpeedIndices = false;
        }



        $speedIndicesCounter = [
          'registers' => $speedIndicesCounter,
          'valid' => $validSpeedIndices
        ];



        $windConfigsM2 = WindM2Config::where('process_id', '=', $id)->get(); 
        $windConfigsM2Counter = $windConfigsM2->count();

        foreach ($windConfigsM2 as $windConfigM2) {
            if ($windConfigM2->wSpeed_min > $windConfigM2->wSpeed_max) {
              $isValid = false;
              array_push($errors, "La velocidad mínima no puede ser superior a la máxima en el modelo 2 de viento llamado " . $windConfigM2->nombre_planta . ".");
            }
        }




        $monthsCounter = 12;
        
        $speedIndicesM2Counter = [];

        foreach ($windConfigs as $windConfig) {

          if ($windConfig->speed_in > $windConfig->speed_rated) {
            $isValid = false;
            array_push($errors, "La velocidad de entrada en la planta eólica ". $windConfig->planta ." debe de ser menor a la velocidad nominal.");
          }

          if ($windConfig->speed_out > $windConfig->speed_rated) {
            $isValid = false;
            array_push($errors, "La velocidad de salida no puede ser mayor a velocidad nominal."); 
          }

            $speedIndicesByWindConfig = SpeedIndicesM2::where('wind_config_id', '=', $windConfig->id)->join('wind_m2_configs', 'wind_m2_configs.id', '=', 'speed_indices_m2s.wind_config_id')->get();
            $speedIndicesByWindConfigCounter = $speedIndicesByWindConfig->count();

            array_push($speedIndicesM2Counter, [
                'wind_config_name' => $windConfig->planta,
                'wind_config_id' => $windConfig->id,
                'speedIndicesByWindConfigCounter' => $speedIndicesByWindConfigCounter,
                'blocksCounter' => $blocksCounter,
                'valid' => ($blocksCounter * $monthsCounter) === $speedIndicesByWindConfigCounter
           ]);     
        }


        $validSpeedIndicesM2 = true;
        foreach ($speedIndicesM2Counter as $speedIndicesM2Register) {
              if (!$speedIndicesM2Register['valid']) {
                $validSpeedIndicesM2 = false;
              }
        }

        if ($speedIndicesM2Counter < 1) {
          $validSpeedIndicesM2 = false;
        }



        $speedIndicesM2Counter = [
          'registers' => $speedIndicesM2Counter,
          'valid' => $validSpeedIndices
        ];



        $areasCounter = Areas::where('process_id', '=', $id)->count();
        $scenarioCounter = Scenario::where('process_id', '=', $id)->count();        
        $horizontsCounter = Horizont::where('process_id', '=', $id)->count();
        $fuelCostPlantsCounter = $fuelCostPlants->count();
        $thermalConfigs = ThermalConfig::where('process_id', '=', $id);
        $thermalConfigCounter = $thermalConfigs->count();

        $smallConfigs = SmallConfig::where('process_id', '=', $id);
        $smallConfigsCounter = $smallConfigs->count();


//EN LAS EXPANSIONES PLANTAS TERMICAS LA GENERACIÓN MAXIMA PUEDE SER MAXIMO EL VALOR DE LA Capacidad
//LO MISMO EN PLANTAS MENORES

        foreach ($thermalConfigs->get() as $thermalConfig) {
            if ($thermalConfig->capacity < 0) {
              $valid = false;
              array_push($errors, "La capacidad de la planta térmica ". $thermalConfig->name . " no puede ser menor a 0 MW.");
            }

            if ($thermalConfig->gen_min > $thermalConfig->gen_max) {

                $valid = false;
                array_push($errors, "La generación mínima de la planta térmica  ". $thermalConfig->name . " no puede ser mayor a la generación máxima.");  
            }

            if ($thermalConfig->gen_min < 0) {
                $valid = false;
                array_push($errors, "La generación mínima de la planta térmica  ". $thermalConfig->name . " no puede ser menor a cero.");  
            }

            if($thermalConfig->gen_max > $thermalConfig->capacity) {
                $valid = false;

                array_push($errors, "La generación máxima de ". $thermalConfig->name . " no puede ser superior a la capacidad.");  

            }


        }

       $storageConfigs = storage_config::where('process_id', '=', $id)->get();

       foreach ($storageConfigs as $storageConfig) {
         if ($storageConfig->min_storage > $storageConfig->max_storage) {
            array_push($errors, "El almacenamiento mínimo no puede ser mayor al almacenamiento máximo en el sistema de alamcenamiento con nombre " .  $storageConfig->name);
            $isValid = false;
         }

         if ($storageConfig->initial_storage < $storageConfig->min_storage) {
            array_push($errors, "El almacenamiento inicial debe de ser mayor o igual al almacenamiento mínimo en el sistema de almacenamiento con nombre " . $storageConfig->name);
            $isValid = false;
         }

         if ($storageConfig->initial_storage > $storageConfig->max_storage) {
            array_push($errors,"El almacenamiento inicial debe de ser menor o igual al almacenamiento máximo en el sistema de almacenamiento con nombre ". $storageConfig->name);
            $isValid = false;
         }

       }



        $thermalExpansions = Thermal_expn::where('thermal_configs.process_id', '=', $id)
            ->join('thermal_configs', 'thermal_configs.id', '=', 'thermal_expns.thermal_config_id')
            ->select('thermal_configs.*')
            ->get();


        foreach ($thermalExpansions as $thermalExpansion) {
          if ($thermalExpansion->gen_max > $thermalExpansion->capacity) {
                $valid = false;
                array_push($errors, "La generación máxima puede ser máximo el valor de la capacidad en la expansion térmica ". $thermalExpansion->name . '.');        
          }

          if ($thermalExpansion->gen_min > $thermalExpansion->gen_max) {
                $valid = false;
                array_push($errors, "La generación mínima no puede ser superior a la máxima en la planta". $thermalExpansion->name .'.');        
          }

        }


        foreach ($smallConfigs->get() as $smallConfig) {

            if ($smallConfig->max < 0) {
              $valid = false;
              array_push($errors, "La capacidad de la planta menor ". $smallConfig->planta_menor . " no puede ser menor a 0 MW.");
            }

            if ($smallConfig->gen_min > $smallConfig->gen_max) {

                $valid = false;
                array_push($errors, "La generación mínima de la planta menor  ". $smallConfig->planta_menor . " no puede ser mayor a la generación máxima.");  
            }

            if ($smallConfig->gen_min < 0) {
                $valid = false;
                array_push($errors, "La generación mínima de la planta menor  ". $smallConfig->planta_menor . " no puede ser menor a cero.");  
            }

            if($smallConfig->gen_max > $smallConfig->max) {
                $valid = false;

                array_push($errors, "La generación máxima de la planta menor ". $smallConfig->planta_menor . " no puede ser superior a la capacidad.");  
            }

      }




      



      


        $lines = Lines::where('process_id', '=', $id)->get();
        $LinesExpansion = LinesExpansion::where('process_id', '=', $id)->get();

        $linesValid = true;
        foreach ($lines as $line) {
          if (!$line->a_initial || !$line->b_final) {
            $linesValid = false;
          }
        }


        if ($areasCounter < 1) {
          $valid = false;
          array_push($errors, 'Debes de definir áreas.');
        }

        if ($horizontsCounter < 1) {
          $valid = false;
          array_push($errors, 'Debes de definir un horizonte.');
        }
        if ($nationalAcum == "0") {
          $valid = false;
          array_push($errors, 'Todos los valores de demanda no pueden estar en 0.');
        }





        if (!$demandCounter['valid']) {
          $valid = false;
          array_push($errors, 'No se ha definido la demanda.');
        }

        if ($fuelCostPlantsCounter < 1) {
          $valid = false;
          array_push($errors, 'Debes de definir combustibles.');
        }

        if ($segmentsCounter < 1) {
          $valid = false;
          array_push($errors, 'Debes de definir segmento.');
        }

        if ($scenarioCounter < 1) {
          $valid = false;
          array_push($errors, 'Debes de definir al menos un escenario.');
        }


        if ($blocksCounter < 1) {
          $valid = false;
          array_push($errors, 'Debes de definir bloques.');
        }

        if ((int)$acumBlockDuration !== 1) {
          $valid = false;
          array_push($errors, 'La suma de las duraciones de los bloques debe de ser igual a 1.');          
        }

        if ($thermalConfigCounter < 1) {
          $valid = false;
          array_push($errors, 'Debes de definir una planta térmica.');
        }


        if (!$rationingCounter['valid']) {
          $valid = false;
          array_push($errors, 'Debes definir el racionamiento.');
        }



        if (!$hydroConfigCounter) {
          $valid = false;
          array_push($errors, 'Debes definir una planta hidroeléctrica.'); 
        }

        if (!$inflowCounter['valid']) {
          $valid = false;
          array_push($errors, 'Debes definir los afluentes hídricos de todas las plantas.'); 
        }


        if (!$inflowWindCounter['valid']&& !$process->generate_wind) {
          $valid = false;
          array_push($errors, 'Debes definir la velocidad del viento de todas las plantas.'); 
        }


        
        if ((!$validSpeedIndices && !$process->generate_wind) && $windConfigCounter > 0) {
          $valid = false;
          array_push($errors, 'Debes definir indices de velocidad.'); 
        }

        if ((!$validSpeedIndicesM2 && !$process->generate_wind) && $windConfigsM2Counter > 0) {
          $valid = false;
          array_push($errors, 'Debes definir indices de velocidad para el modelo 2.'); 
        }


        if (!$linesValid) {
          $valid = false;
          array_push($errors, 'Las redes de transmisión no estan conectadas correctamente.'); 
        }


        return [
            'process' => $process,
            'valid' => $valid,
            'errors' => $errors,
            'demandCounter' => $demandCounter,
            'fuelCounter' => $fuelCounter,
            'rationingCounter' => $rationingCounter,
            'inflowCounter' => $inflowCounter,
            'speedIndicesCounter' => $speedIndicesCounter,
            'speedIndicesM2Counter' => $speedIndicesM2Counter,
            'inflowWindCounter' => $inflowWindCounter,
            'inflowWindCounterM2' => $inflowWindCounterM2
        ];

    }
    
    /**
     * Permite actualizar el segmento
     * GET|HEAD /segments
     *
     * @param Request $request
     * @return Response
     */
    public function changeSegment (Request $request) {
        $input = $request->all();
        $process = Process::find($input['id']);
        $process->segment_id =  $input['segment_id'];
        $process->save();
    }

    /**
     * Display a listing of the process.
     * GET|HEAD /processes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, $per_page = 10)
    {
        $this->processRepository->pushCriteria(new RequestCriteria($request));
        $this->processRepository->pushCriteria(new LimitOffsetCriteria($request));
        $processes = $this->processRepository->paginate($request->query('limit'));

        return $this->sendResponse($processes->toArray(), 'Processes retrieved successfully');
    }

    /**
     * Store a newly created process in storage.
     * POST /processes
     *
     * @param CreateprocessAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        DB::table('user_meta')->where('user_id', '=', $input['userId'])->decrement('models');

        if (!isset($input['template_id'])) {
            // Debemos de obtener numero de escenarios

            $process = \App\Models\Process::create($input);
            // miramos si el tipo de proyecto es plantilla
            if ($process->type === "En blanco") {
                try {
                  for ($i=0; $i < $input['blocks']; $i++) { 
                      Blocks::create([
                        'name' => 'Bloque ' . ($i + 1),
                        'duration' => 0,
                        'participation' => 0,
                        'storage_restrictions' => 0,
                        'process_id' => $process->id
                      ]);
                  }      

                  for ($i=0; $i < $input['scenarios']; $i++) { 
                      Scenario::create([
                        'name' => 'Escenario ' . ($i + 1),
                        'process_id' => $process->id                
                      ]);
                  }

                } catch (Exception $e) {
                  return $e;
                } 
            } 

            if ($process->type === "Excel") {
               \File::move(storage_path("app/temporal/".$input['fileCode']. ".xlsx"), storage_path("app/temporal/".$process->id.".xlsx"));
            }

            return $this->sendResponse($process->toArray(), 'Process saved successfully');

        } else {
            
            // Obtenemos el proceso a crear
            $templateModel = Process::where('id', '=', $input['template_id'])->first();
            
            $model = new \App\Services\ClonateService();
            $process = $model->clonate($input['name'],$input['template_id'],$input['userId']);

            // siempre siendo del tipo que sea clonamos la carpeta

            if ($process->type === "Excel") {

              //copiamos el directorio asociado a la copia
             $this->Filesystem->copyDirectory(storage_path() . '/app/models/' . $templateModel->userId . '/'. $input['template_id'] . '/', storage_path() . '/app/models/' . $process->userId . '/' . $process->id);

             // movemos el archivo excel
             \File::move(storage_path() . '/app/models/' . $templateModel->userId . '/'. $input['template_id'] . '/python/datasystem/'. $templateModel->id .'.xlsx', storage_path() . '/app/models/' . $process->userId . '/' . $process->id . '/python/datasystem/' . $process->id . '.xlsx');

           }


            return $this->sendResponse($process->toArray(), 'Process saved successfully');
        }

    }

    /**
     * Display the specified process.
     * GET|HEAD /processes/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var process $process */
        $process = $this->processRepository->findWithoutFail($id);

        if (empty($process)) {
            return $this->sendError('Process not found');
        }

        return $this->sendResponse($process->toArray(), 'Process retrieved successfully');
    }

    /**
     * Update the specified process in storage.
     * PUT/PATCH /processes/{id}
     *
     * @param  int $id
     * @param UpdateprocessAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateprocessAPIRequest $request)
    {
        $input = $request->all();

        /** @var process $process */
        $process = $this->processRepository->findWithoutFail($id);

        if (empty($process)) {
            return $this->sendError('Process not found');
        }

        $process->update($input);

        if ($process->type === "Excel") {
          if (isset($input['fileCode'])) {
              \File::move(storage_path("app/temporal/".$input['fileCode']. ".xlsx"), storage_path("app/temporal/".$process->id.".xlsx"));
            
          }
        }



        return $this->sendResponse($process->toArray(), 'process updated successfully');
    }

    /**
     * Remove the specified process from storage.
     * DELETE /processes/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var process $process */
        $process = $this->processRepository->findWithoutFail($id);

        DB::table('user_meta')->where('user_id', '=', $process->userId)->increment('models');

        if (empty($process)) {
            return $this->sendError('Process not found');
        }
        
        // es posible que tengamos que eliminar todo antes de eliminar el proceso.
        \App\Models\SpeedIndicesM2::where('process_id', '=', $id)->delete();
        \App\Models\thermalConfig::where('process_id', '=', $id)->delete(); 
        \App\Models\SmallConfig::where('process_id', '=', $id)->delete();
        \App\Models\FuelCostPlant::where('process_id', '=', $id)->delete();
        \App\Models\HidroConfig::where('process_id', '=', $id)->delete();
        \App\Models\InflowWind::where('process_id', '=', $id)->delete();
        \App\Models\Lines::where('process_id', '=', $id)->delete();
        \App\Models\LinesExpansion::where('process_id', '=', $id)->delete();        
        \App\Models\WindM2Config::where('process_id', '=', $id)->delete();        
        \App\Models\Demand::where('process_id', '=', $id)->delete();        
        \App\Models\speedIndices::where('process_id', '=', $id)->delete();        
        \App\Models\windConfig::where('process_id', '=', $id)->delete();        
        \App\Models\storage_config::where('process_id', '=', $id)->delete();
        \App\Models\Areas::where('process_id', '=', $id)->delete();
        \App\Models\Blocks::where('process_id', '=', $id)->delete();

        $process->segment_id = null;

        $process->save();

        $process->delete();

        return $this->sendResponse($id, 'Process deleted successfully');
    }


 /**
     * Permite iniciar la ejecución de un modelo
     *
     * @param  int $id
     *
     * @return Response
     */
    public function process ($id) {

        /*=============================================
        = OBTENEMOS EL PROCESO
        =============================================*/
        $process = $this->processRepository->find($id);
        $queue = [
            new ProcessPythonScript($process)
        ];


        // Determinamos si debemos de generar un excel


        if ($process->type !== "Excel") {
          array_unshift($queue, new GenerateExcel($process->id));
        }

        // ME INTERESA SABER SI HAY QUE GENERAR DATOS DE VIENTO
        if($process->generate_wind) {
          // SI HAY QUE GENERAR DATOS DE VIENTO DEBEMOS AGREGAR A LA COLA
          // EL ELEMENTO DE LA COLA DE ANGELICA (REVISAR PARAMETROS DE ENVIO)
          //PROCESAMOS DATOS DE VIENTO Y DE HYDRO          
          array_unshift($queue, new ProcessRScript($process));
        }

        $data = [
            'name' => $process->name,
            'userId' => $process->userId,
            'phase' => 2,
            'statusId' => $process->statusId,
            'templateId' => $process->templateId,
            'max_iter' => $process->max_iter,
            'extra_stages' => $process->extra_stages,
            'stages' => $process->stages,
            'seriesBack' => $process->seriesBack,
            'seriesForw' => $process->seriesForw,
            'stochastic' => $process->stochastic,
            'variance' => $process->variance,
            'sensDem' => $process->sensDem,
            'speed_out' => $process->speed_out,
            'speed_in' => $process->speed_in,
            'eps_area' => $process->eps_area,
            'eps_all' => $process->eps_all,
            'eps_risk' => $process->eps_risk,
            'commit' => $process->commit,
            'lag_max' => $process->lag_max,
            'testing_t' => $process->testing_t,
            'd_correl' => $process->d_correl,
            'seasonality' => $process->seasonality
        ];

        // Registramos los procesos en el servidor de colas
        PrepareCalculator::withChain($queue)->dispatch($process);

        // Retornamos la respuesta al usuario
        return response()->json([
            'status' => 'success',
            'model' => $process,
            'message' => 'El proceso ha sido creado, actualmente se encuentra en espera.'
        ]);

    }


}
