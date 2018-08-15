<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAreasAPIRequest;
use App\Http\Requests\API\UpdateAreasAPIRequest;
use App\Models\Areas;
use App\Repositories\AreasRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Routing\Controller as BaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Excel;

/**
 * Class AreasController
 * @package App\Http\Controllers\API
 */

class AreasAPIController extends BaseController
{
    /** @var  AreasRepository */
    private $areasRepository;

    public function __construct(AreasRepository $areasRepo)
    {
        $this->areasRepository = $areasRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the Areas.
     * GET|HEAD /areas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->areasRepository->pushCriteria(new RequestCriteria($request));
        $this->areasRepository->pushCriteria(new LimitOffsetCriteria($request));
        $areas = $this->areasRepository->all();

        return $this->sendResponse($areas->toArray(), 'Areas retrieved successfully');
    }

    /**
     * Store a newly created Areas in storage.
     * POST /areas
     *
     * @param CreateAreasAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAreasAPIRequest $request)
    {
        $input = $request->all();

        $areas = $this->areasRepository->create($input);

        return $this->sendResponse($areas->toArray(), 'Areas saved successfully');
    }

    /**
     * Display the specified Areas.
     * GET|HEAD /areas/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Areas $areas */
        $areas = $this->areasRepository->findWithoutFail($id);

        if (empty($areas)) {
            return $this->sendResponse('Areas not found');
        }

        return $this->sendResponse($areas->toArray(), 'Areas retrieved successfully');
    }

    /**
     * Update the specified Areas in storage.
     * PUT/PATCH /areas/{id}
     *
     * @param  int $id
     * @param UpdateAreasAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAreasAPIRequest $request)
    {
        $input = $request->all();

        /** @var Areas $areas */
        $areas = $this->areasRepository->findWithoutFail($id);

        if (empty($areas)) {
            return $this->sendResponse('Areas not found');
        }

        $areas = $this->areasRepository->update($input, $id);

        return $this->sendResponse($areas->toArray(), 'Areas updated successfully');
    }

    /**
     * Remove the specified Areas from storage.
     * DELETE /areas/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Areas $areas */
        $areas = $this->areasRepository->findWithoutFail($id);

        if (empty($areas)) {
            return $this->sendResponse('Areas not found');
        }

        $areas->delete();

        return $this->sendResponse($id, 'Areas deleted successfully');
    }


    /**
     * Permite generar un excel con registros
     * DELETE /areas/generateExcel
     *
     * @param  array $ids
     *
     * @return Response
     */
    public function generateExcel ($id) {

       $areasByProcessId = Areas::where('process_id', '=', $id)->get(['id','name']);

        $data = [];
        $app = app();

       foreach ($areasByProcessId as $key => $value) {
         $formatted = $app->make('stdClass');
         $formatted->id = $value->id;             
         $formatted->name = $value->name;
         array_push($data, (array) $formatted);
       }


        $myFile = Excel::create('Areas', function($excel) use ($data) {
            $excel->sheet('Areas', function($sheet) use ($data) {
                 $sheet->fromArray($data);
            });
        });

        $myFile = $myFile->string('xlsx');

        $response =  array(
            'name' => "Areas",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) 
        );
        return response()->json($response);
    }

    /**
     * Permite importar un archivo excel
     * POST /areas/importExcel
     *
     * @param  array $ids
     *
     * @return Response
     */
    public function importExcel ($id) {
        try {
            
            // obtenemos el archivo
            $file = \Request::file('file');
            // obtenemos el path
            $path = storage_path() . '/categories/';
            // obtenemos el nombre del archivo
            $fileName = 'areas_' .   $id . '.xlsx';
            // movemos el archivo
            $file->move($path, $fileName);
            // generamos los datos
            $data = Excel::load($path . $fileName)->get();
            // borramos areas
            Areas::where('process_id', $id)->delete();
            //importamos areas nuevas
            // insertamos
            foreach ($data as $row) {
              $row->process_id = $id;
              $areas = $this->areasRepository->create([
                'name' => $row->name,
                'process_id' => $id 
              ]);
            }

            return  Areas::where('process_id', $id)->get();

        } catch (Exception $e) {
            dd($e); 
        }       
    }
}
