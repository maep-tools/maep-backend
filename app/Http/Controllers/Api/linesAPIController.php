<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatelinesAPIRequest;
use App\Http\Requests\API\UpdatelinesAPIRequest;
use App\Models\lines;
use App\Repositories\linesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;

/**
 * Class linesController
 * @package App\Http\Controllers\API
 */

class linesAPIController extends BaseController
{
    /** @var  linesRepository */
    private $linesRepository;

    public function __construct(linesRepository $linesRepo)
    {
        $this->linesRepository = $linesRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the lines.
     * GET|HEAD /lines
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->linesRepository->pushCriteria(new RequestCriteria($request));
        $this->linesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $lines = $this->linesRepository->all();

        return $this->sendResponse($lines->toArray(), 'Lines retrieved successfully');
    }

    /**
     * Store a newly created lines in storage.
     * POST /lines
     *
     * @param CreatelinesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatelinesAPIRequest $request)
    {
        $input = $request->all();

        $lines = $this->linesRepository->create($input);

        return $this->sendResponse($lines->toArray(), 'Lines saved successfully');
    }

    /**
     * Display the specified lines.
     * GET|HEAD /lines/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var lines $lines */
        $lines = $this->linesRepository->findWithoutFail($id);

        if (empty($lines)) {
            return $this->sendError('Lines not found');
        }

        return $this->sendResponse($lines->toArray(), 'Lines retrieved successfully');
    }

    /**
     * Update the specified lines in storage.
     * PUT/PATCH /lines/{id}
     *
     * @param  int $id
     * @param UpdatelinesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatelinesAPIRequest $request)
    {
        $input = $request->all();

        /** @var lines $lines */
        $lines = $this->linesRepository->findWithoutFail($id);

        if (empty($lines)) {
            return $this->sendError('Lines not found');
        }

        $lines = $this->linesRepository->update($input, $id);

        return $this->sendResponse($lines->toArray(), 'lines updated successfully');
    }

    /**
     * Remove the specified lines from storage.
     * DELETE /lines/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var lines $lines */
        $lines = $this->linesRepository->findWithoutFail($id);

        if (empty($lines)) {
            return $this->sendError('Lines not found');
        }

        $lines->delete();

        return $this->sendResponse($id, 'Lines deleted successfully');
    }
}
