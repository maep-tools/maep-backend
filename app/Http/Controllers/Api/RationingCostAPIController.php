<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRationingCostAPIRequest;
use App\Http\Requests\API\UpdateRationingCostAPIRequest;
use App\Models\RationingCost;
use App\Repositories\RationingCostRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use App\Models\Horizont;

/**
 * Class RationingCostController
 * @package App\Http\Controllers\API
 */

class RationingCostAPIController extends BaseController
{
    /** @var  RationingCostRepository */
    private $rationingCostRepository;

    public function __construct(RationingCostRepository $rationingCostRepo)
    {
        $this->rationingCostRepository = $rationingCostRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    
    /**
     * Display a listing of the RationingCost.
     * GET|HEAD /rationingCosts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->rationingCostRepository->pushCriteria(new RequestCriteria($request));
        $this->rationingCostRepository->pushCriteria(new LimitOffsetCriteria($request));
        $rationingCosts = $this->rationingCostRepository->all();

        return $this->sendResponse($rationingCosts->toArray(), 'Rationing Costs retrieved successfully');
    }

    /**
     * Store a newly created RationingCost in storage.
     * POST /rationingCosts
     *
     * @param CreateRationingCostAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRationingCostAPIRequest $request)
    {
        $input = $request->all();

        $rationingCosts = $this->rationingCostRepository->create($input);

        return $this->sendResponse($rationingCosts->toArray(), 'Rationing Cost saved successfully');
    }

    /**
     * Display the specified RationingCost.
     * GET|HEAD /rationingCosts/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RationingCost $rationingCost */
        $rationingCost = $this->rationingCostRepository->findWithoutFail($id);

        if (empty($rationingCost)) {
            return $this->sendError('Rationing Cost not found');
        }

        return $this->sendResponse($rationingCost->toArray(), 'Rationing Cost retrieved successfully');
    }

    /**
     * Update the specified RationingCost in storage.
     * PUT/PATCH /rationingCosts/{id}
     *
     * @param  int $id
     * @param UpdateRationingCostAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRationingCostAPIRequest $request)
    {
        $input = $request->all();

        /** @var RationingCost $rationingCost */
        $rationingCost = $this->rationingCostRepository->findWithoutFail($id);

        if (empty($rationingCost)) {
            return $this->sendError('Rationing Cost not found');
        }

        $rationingCost = $this->rationingCostRepository->update($input, $id);

        return $this->sendResponse($rationingCost->toArray(), 'RationingCost updated successfully');
    }

    /**
     * Remove the specified RationingCost from storage.
     * DELETE /rationingCosts/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RationingCost $rationingCost */
        $rationingCost = $this->rationingCostRepository->findWithoutFail($id);

        if (empty($rationingCost)) {
            return $this->sendError('Rationing Cost not found');
        }

        $rationingCost->delete();

        return $this->sendResponse($id, 'Rationing Cost deleted successfully');
    }


    public function getRationingCostBySegmentId ($id) {
        try {
        return Horizont::leftJoin('rationing_costs', 'rationing_costs.horizont_id', '=', 'horizonts.id')
        ->leftJoin('segments', 'rationing_costs.segment_id', '=', 'segments.id')
        ->where('segments.id', '=', $id)
        ->select('rationing_costs.id as rationing_costs_id', 'rationing_costs.value', 'rationing_costs.horizont_id', 'rationing_costs.value', 'rationing_costs.segment_id')
        ->get();         
        } catch (Exception $e) {
            return $e;
        }
    }
}
