<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEntranceStagesAPIRequest;
use App\Http\Requests\API\UpdateEntranceStagesAPIRequest;
use App\Models\EntranceStages;
use App\Repositories\EntranceStagesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;

/**
 * Class EntranceStagesController
 * @package App\Http\Controllers\API
 */

class EntranceStagesAPIController extends BaseController
{
    /** @var  EntranceStagesRepository */
    private $entranceStagesRepository;

    public function __construct(EntranceStagesRepository $entranceStagesRepo)
    {
        $this->entranceStagesRepository = $entranceStagesRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the EntranceStages.
     * GET|HEAD /entranceStages
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->entranceStagesRepository->pushCriteria(new RequestCriteria($request));
        $this->entranceStagesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $entranceStages = $this->entranceStagesRepository->all();

        return $this->sendResponse($entranceStages->toArray(), 'Entrance Stages retrieved successfully');
    }

    /**
     * Store a newly created EntranceStages in storage.
     * POST /entranceStages
     *
     * @param CreateEntranceStagesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEntranceStagesAPIRequest $request)
    {
        $input = $request->all();

        $entranceStages = $this->entranceStagesRepository->create($input);

        return $this->sendResponse($entranceStages->toArray(), 'Entrance Stages saved successfully');
    }

    /**
     * Display the specified EntranceStages.
     * GET|HEAD /entranceStages/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EntranceStages $entranceStages */
        $entranceStages = $this->entranceStagesRepository->findWithoutFail($id);

        if (empty($entranceStages)) {
            return $this->sendError('Entrance Stages not found');
        }

        return $this->sendResponse($entranceStages->toArray(), 'Entrance Stages retrieved successfully');
    }

    /**
     * Update the specified EntranceStages in storage.
     * PUT/PATCH /entranceStages/{id}
     *
     * @param  int $id
     * @param UpdateEntranceStagesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEntranceStagesAPIRequest $request)
    {
        $input = $request->all();

        /** @var EntranceStages $entranceStages */
        $entranceStages = $this->entranceStagesRepository->findWithoutFail($id);

        if (empty($entranceStages)) {
            return $this->sendError('Entrance Stages not found');
        }

        $entranceStages = $this->entranceStagesRepository->update($input, $id);

        return $this->sendResponse($entranceStages->toArray(), 'EntranceStages updated successfully');
    }

    /**
     * Remove the specified EntranceStages from storage.
     * DELETE /entranceStages/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EntranceStages $entranceStages */
        $entranceStages = $this->entranceStagesRepository->findWithoutFail($id);

        if (empty($entranceStages)) {
            return $this->sendError('Entrance Stages not found');
        }

        $entranceStages->delete();

        return $this->sendResponse($id, 'Entrance Stages deleted successfully');
    }
}
