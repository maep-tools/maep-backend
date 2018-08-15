<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatelinesExpansionAPIRequest;
use App\Http\Requests\API\UpdatelinesExpansionAPIRequest;
use App\Models\linesExpansion;
use App\Repositories\linesExpansionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;

/**
 * Class linesExpansionController
 * @package App\Http\Controllers\API
 */

class linesExpansionAPIController extends BaseController
{
    /** @var  linesExpansionRepository */
    private $linesExpansionRepository;

    public function __construct(linesExpansionRepository $linesExpansionRepo)
    {
        $this->linesExpansionRepository = $linesExpansionRepo;
    }


    public function sendResponse ($e) {
        return response()->json($e);
    }
    

    /**
     * Display a listing of the linesExpansion.
     * GET|HEAD /linesExpansions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->linesExpansionRepository->pushCriteria(new RequestCriteria($request));
        $this->linesExpansionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $linesExpansions = $this->linesExpansionRepository->all();

        return $this->sendResponse($linesExpansions->toArray(), 'Lines Expansions retrieved successfully');
    }

    /**
     * Store a newly created linesExpansion in storage.
     * POST /linesExpansions
     *
     * @param CreatelinesExpansionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatelinesExpansionAPIRequest $request)
    {
        $input = $request->all();

        $linesExpansions = $this->linesExpansionRepository->create($input);

        return $this->sendResponse($linesExpansions->toArray(), 'Lines Expansion saved successfully');
    }

    /**
     * Display the specified linesExpansion.
     * GET|HEAD /linesExpansions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var linesExpansion $linesExpansion */
        $linesExpansion = $this->linesExpansionRepository->findWithoutFail($id);

        if (empty($linesExpansion)) {
            return $this->sendResponse('Lines Expansion not found');
        }

        return $this->sendResponse($linesExpansion->toArray(), 'Lines Expansion retrieved successfully');
    }

    /**
     * Update the specified linesExpansion in storage.
     * PUT/PATCH /linesExpansions/{id}
     *
     * @param  int $id
     * @param UpdatelinesExpansionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatelinesExpansionAPIRequest $request)
    {
        $input = $request->all();

        /** @var linesExpansion $linesExpansion */
        $linesExpansion = $this->linesExpansionRepository->findWithoutFail($id);

        if (empty($linesExpansion)) {
            return $this->sendResponse('Lines Expansion not found');
        }

        $linesExpansion = $this->linesExpansionRepository->update($input, $id);

        return $this->sendResponse($linesExpansion->toArray(), 'linesExpansion updated successfully');
    }

    /**
     * Remove the specified linesExpansion from storage.
     * DELETE /linesExpansions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var linesExpansion $linesExpansion */
        $linesExpansion = $this->linesExpansionRepository->findWithoutFail($id);

        if (empty($linesExpansion)) {
            return $this->sendResponse('Lines Expansion not found');
        }

        $linesExpansion->delete();

        return $this->sendResponse($id, 'Lines Expansion deleted successfully');
    }
}
