<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Routing\Controller as BaseController;
use Response;
use App\Models\Process;
use Excel;

class ResultsController extends BaseController
{

    public function sendResponse ($e) {
        return response()->json($e);
    }

    public function getMainChart($id) {
      $process = Process::find($id);
      if (\File::exists(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/python/results/areasdispatch_reportDataGeneral.txt')) {
        return \File::get(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/python/results/areasdispatch_reportDataGeneral.txt');
      }
    }
    public function getAreasChart($id) {
      $process = Process::find($id);      
      $files = \File::allFiles(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/python/results/areasdispatch_reportData/');

      $chartByArea = [];
      foreach ($files as $file) {
        $infoFile = pathinfo($file);
        array_push($chartByArea, [
            'area' => $infoFile["filename"],
            'chart' => \File::get($file)
        ]);
      }

      return $chartByArea;
    }


    public function getWindDataInflowWind ($id) {
        $process = Process::find($id);
        return response()->download(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/rscript/test/Forecast_data/Inflow.csv', "InflowWind.csv");
    }
    public function getWindDataInflow ($id) {
        $process = Process::find($id);
        return response()->download(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/rscript/test/Forecast_data/Inflow.csv', "Inflow.csv");
    }
    public function getLogs($id) {
       $process = Process::find($id);
       if (\File::exists(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/python/process.txt')) {
         return \File::get(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/python/process.txt');
       }
    }

    public function getErrors ($id) {
       $process = Process::find($id);
       if (\File::exists(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/python/results/error.txt')) {
         return \File::get(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/python/results/error.txt');
       }
    }

    public function getGeneralData($id) {
       $process = Process::find($id);
        return response()->download(storage_path() . '/app/models/'.$process->userId.'/'.$id.'/python/results/General_results.xlsx', "General.xlsx");
    }
}
