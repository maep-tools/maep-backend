<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateinflowAPIRequest;
use App\Http\Requests\API\UpdateinflowAPIRequest;
use App\Models\inflow;
use App\Repositories\inflowRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use App\Models\Horizont;

/**
 * Class inflowController
 * @package App\Http\Controllers\API
 */

class inflowAPIController extends BaseController
{
    /** @var  inflowRepository */
    private $inflowRepository;

    public function __construct(inflowRepository $inflowRepo)
    {
        $this->inflowRepository = $inflowRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    
    /**
     * Display a listing of the inflow.
     * GET|HEAD /inflows
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->inflowRepository->pushCriteria(new RequestCriteria($request));
        $this->inflowRepository->pushCriteria(new LimitOffsetCriteria($request));
        $inflows = $this->inflowRepository->all();

        return $this->sendResponse($inflows->toArray(), 'Inflows retrieved successfully');
    }

    /**
     * Store a newly created inflow in storage.
     * POST /inflows
     *
     * @param CreateinflowAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateinflowAPIRequest $request)
    {
        $input = $request->all();

        $inflows = $this->inflowRepository->create($input);

        return $this->sendResponse($inflows->toArray(), 'Inflow saved successfully');
    }

    /**
     * Display the specified inflow.
     * GET|HEAD /inflows/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var inflow $inflow */
        $inflow = $this->inflowRepository->findWithoutFail($id);

        if (empty($inflow)) {
            return $this->sendError('Inflow not found');
        }

        return $this->sendResponse($inflow->toArray(), 'Inflow retrieved successfully');
    }

    /**
     * Update the specified inflow in storage.
     * PUT/PATCH /inflows/{id}
     *
     * @param  int $id
     * @param UpdateinflowAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateinflowAPIRequest $request)
    {
        $input = $request->all();

        /** @var inflow $inflow */
        $inflow = $this->inflowRepository->findWithoutFail($id);

        if (empty($inflow)) {
            return $this->sendError('Inflow not found');
        }

        $inflow = $this->inflowRepository->update($input, $id);

        return $this->sendResponse($inflow->toArray(), 'inflow updated successfully');
    }

    /**
     * Remove the specified inflow from storage.
     * DELETE /inflows/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var inflow $inflow */
        $inflow = $this->inflowRepository->findWithoutFail($id);

        if (empty($inflow)) {
            return $this->sendError('Inflow not found');
        }

        $inflow->delete();

        return $this->sendResponse($id, 'Inflow deleted successfully');
    }



    public function getInflowByHydroConfigId($id)  {
        return Horizont::leftJoin('inflows', 'inflows.horizont_id', '=', 'horizonts.id')
            ->leftJoin('scenarios', 'scenarios.id', '=', 'inflows.scenario_id')
            ->leftJoin('hidro_configs', 'inflows.hidro_config_id', '=', 'hidro_configs.id')
            ->where('hidro_configs.id', '=', $id)
            ->select('scenarios.name','inflows.id', 'inflows.value', 'inflows.horizont_id', 'inflows.hidro_config_id', 'inflows.horizont_id', 'inflows.scenario_id')
            ->get();         

    }


}
