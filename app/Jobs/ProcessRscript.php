<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class ProcessRscript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

      protected $data;

      public $timeout = 120;

      public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    DB::table('processes')
            ->where('id', '=',  $this->data->id)
            ->update(['phase' => 2]);


    $windDataCSV = [];

    $windColumnsCSV = ["codigo" ,"H/W/S","localizacion","resolucion","nombre","p_instalada (MW)","FP (MWh/m3/s)"];
    

    array_push($windDataCSV, $windColumnsCSV);

    $windConfigs = \App\Models\WindConfig::where('process_id','=', $this->data->id)->get();

    foreach ($windConfigs as $windConfig) {
        // por cada uno de estos debemos de ir moviendo o copiando el archivo a la carpeta
         \File::copy(storage_path("app/generate/wind/".$windConfig->id. ".csv"), storage_path() .'/app/models/' . $this->data->userId . '/' . $this->data->id . '/rscript/main/series_hidricas_sistema_completo/' . $windConfig .'.csv');



        array_push($windDataCSV, [
            $windConfig->id,
            "H",
            $windConfig->area_id,
            12,
            $windConfig->planta,
            $windConfig->p_instalada,
            $windConfig->fp
        ]);
    }

    \Excel::create('info_var_lol', function($excel) use ($windDataCSV) {
        $excel->sheet('jaja', function($sheet) use ($windDataCSV) {
            $sheet->fromArray($windDataCSV, null, 'A1', true, false);
        });

    })->store('csv', storage_path() .'/app/models/' . $this->data->userId . '/' . $this->data->id . '/rscript/main/series_hidricas_sistema_completo/');


    // nos traemos todas las plantasHydro


    // nos traemos todas las plantasWind

    // debemos de escribir el excel de Angelica para hydro y wind

    // debemos mover los archivos a la carpeta



    chdir(storage_path() .'/app/models/' . $this->data->userId . '/' . $this->data->id . '/rscript/test/');


    $command = 'Rscript VAR_main.R ' . $this->data->lag_max. ' ' . $this->data->testing_t . ' ' .$this->data->d_correl.' '.$this->data->seasonality;
        $last_line = exec($command);


        var_dump($last_line);

        $id = (int) $this->data->id;

        DB::table('processes')
            ->where('id', '=', $id)
            ->update(['statusId' => 2]);
    }
}
