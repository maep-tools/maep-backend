<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\API\CreatedemandAPIRequest;
use App\Http\Requests\API\UpdatedemandAPIRequest;
use App\Models\demand;
use App\Repositories\demandRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use App\Models\Horizont;

/**
 * Class demandController
 * @package App\Http\Controllers\API
 */

class DemandAPIController extends BaseController
{
    /** @var  demandRepository */
    private $demandRepository;

    public function __construct(demandRepository $demandRepo)
    {
        $this->demandRepository = $demandRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the demand.
     * GET|HEAD /demands
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->demandRepository->pushCriteria(new RequestCriteria($request));
        $this->demandRepository->pushCriteria(new LimitOffsetCriteria($request));
        $demands = $this->demandRepository->all();

        return $this->sendResponse($demands->toArray(), 'Demands retrieved successfully');
    }


    function getDemandByAreaId ($areaId) {
        try {
        return Horizont::leftJoin('demands', 'demands.Horizont_id', '=', 'horizonts.id')
        ->leftJoin('areas', 'demands.area_id', '=', 'areas.id')
        ->select('demands.area_id', 'demands.factor', 'demands.horizont_id', 'demands.id as demand_id', 'horizonts.national', 'horizonts.period', 'horizonts.process_id', 'horizonts.id as horizont_id')
        ->where('demands.area_id', '=', $areaId)
        ->get();         
        } catch (Exception $e) {
            return $e;
        }

    }


    /**
     * Store a newly created demand in storage.
     * POST /demands
     *
     * @param CreatedemandAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatedemandAPIRequest $request)
    {
        $input = $request->all();

        $demands = $this->demandRepository->create($input);

        return $this->sendResponse($demands->toArray(), 'Demand saved successfully');
    }

    /**
     * Display the specified demand.
     * GET|HEAD /demands/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var demand $demand */
        $demand = $this->demandRepository->findWithoutFail($id);

        if (empty($demand)) {
            return $this->sendResponse('Demand not found');
        }

        return $this->sendResponse($demand->toArray(), 'Demand retrieved successfully');
    }

    /**
     * Update the specified demand in storage.
     * PUT/PATCH /demands/{id}
     *
     * @param  int $id
     * @param UpdatedemandAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatedemandAPIRequest $request)
    {
        $input = $request->all();

        /** @var demand $demand */
        $demand = $this->demandRepository->findWithoutFail($id);

        if (empty($demand)) {
            return $this->sendResponse('Demand not found');
        }

        $demand = $this->demandRepository->update($input, $id);

        return $this->sendResponse($demand->toArray(), 'demand updated successfully');
    }

    /**
     * Remove the specified demand from storage.
     * DELETE /demands/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var demand $demand */
        $demand = $this->demandRepository->findWithoutFail($id);

        if (empty($demand)) {
            return $this->sendResponse('Demand not found');
        }

        $demand->delete();

        return $this->sendResponse($id, 'Demand deleted successfully');
    }
}
