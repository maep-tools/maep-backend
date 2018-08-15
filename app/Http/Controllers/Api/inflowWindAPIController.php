<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateinflowWindAPIRequest;
use App\Http\Requests\API\UpdateinflowWindAPIRequest;
use App\Models\inflowWind;
use App\Repositories\inflowWindRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use App\Models\Horizont;


/**
 * Class inflowWindController
 * @package App\Http\Controllers\API
 */

class inflowWindAPIController extends BaseController
{
    /** @var  inflowWindRepository */
    private $inflowWindRepository;

    public function __construct(inflowWindRepository $inflowWindRepo)
    {
        $this->inflowWindRepository = $inflowWindRepo;
    }


    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the inflowWind.
     * GET|HEAD /inflowWinds
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->inflowWindRepository->pushCriteria(new RequestCriteria($request));
        $this->inflowWindRepository->pushCriteria(new LimitOffsetCriteria($request));
        $inflowWinds = $this->inflowWindRepository->all();

        return $this->sendResponse($inflowWinds->toArray(), 'Inflow Winds retrieved successfully');
    }

    /**
     * Store a newly created inflowWind in storage.
     * POST /inflowWinds
     *
     * @param CreateinflowWindAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateinflowWindAPIRequest $request)
    {
        $input = $request->all();

        $inflowWinds = $this->inflowWindRepository->create($input);

        return $this->sendResponse($inflowWinds->toArray(), 'Inflow Wind saved successfully');
    }

    /**
     * Display the specified inflowWind.
     * GET|HEAD /inflowWinds/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var inflowWind $inflowWind */
        $inflowWind = $this->inflowWindRepository->findWithoutFail($id);

        if (empty($inflowWind)) {
            return $this->sendResponse('Inflow Wind not found');
        }

        return $this->sendResponse($inflowWind->toArray(), 'Inflow Wind retrieved successfully');
    }

    /**
     * Update the specified inflowWind in storage.
     * PUT/PATCH /inflowWinds/{id}
     *
     * @param  int $id
     * @param UpdateinflowWindAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateinflowWindAPIRequest $request)
    {
        $input = $request->all();

        /** @var inflowWind $inflowWind */
        $inflowWind = $this->inflowWindRepository->findWithoutFail($id);

        if (empty($inflowWind)) {
            return $this->sendResponse('Inflow Wind not found');
        }

        $inflowWind = $this->inflowWindRepository->update($input, $id);

        return $this->sendResponse($inflowWind->toArray(), 'inflowWind updated successfully');
    }

    /**
     * Remove the specified inflowWind from storage.
     * DELETE /inflowWinds/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var inflowWind $inflowWind */
        $inflowWind = $this->inflowWindRepository->findWithoutFail($id);

        if (empty($inflowWind)) {
            return $this->sendResponse('Inflow Wind not found');
        }

        $inflowWind->delete();

        return $this->sendResponse($id, 'Inflow Wind deleted successfully');
    }



    public function getInflowWindByWindConfigId($id)  {
        return Horizont::leftJoin('inflow_winds', 'inflow_winds.horizont_id', '=', 'horizonts.id')
            ->leftJoin('wind_configs', 'inflow_winds.wind_config_id', '=', 'wind_configs.id')
            ->leftJoin('scenarios', 'inflow_winds.scenario_id', '=', 'scenarios.id')

            ->where('wind_configs.id', '=', $id)
            ->select('inflow_winds.id', 'scenarios.name', 'inflow_winds.value', 'inflow_winds.horizont_id', 'inflow_winds.wind_config_id', 'inflow_winds.horizont_id', 'inflow_winds.scenario_id')
            ->get(); 
    }


    public function getInflowWindByWindConfigIdM2($id)  {
        return Horizont::leftJoin('inflow_wind_m2s', 'inflow_wind_m2s.horizont_id', '=', 'horizonts.id')
            ->leftJoin('wind_m2_configs', 'inflow_wind_m2s.wind_config_id', '=', 'wind_m2_configs.id')
            ->leftJoin('scenarios', 'inflow_wind_m2s.scenario', '=', 'scenarios.id')
            ->where('wind_m2_configs.id', '=', $id)
            ->select('scenarios.name','inflow_wind_m2s.id', 'inflow_wind_m2s.value', 'inflow_wind_m2s.horizont_id', 'inflow_wind_m2s.wind_config_id', 'inflow_wind_m2s.horizont_id', 'inflow_wind_m2s.scenario')
            ->get(); 


    }


}
