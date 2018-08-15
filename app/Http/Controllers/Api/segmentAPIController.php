<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\API\CreatesegmentAPIRequest;
use App\Http\Requests\API\UpdatesegmentAPIRequest;
use App\Models\segment;
use App\Repositories\segmentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;

/**
 * Class segmentController
 * @package App\Http\Controllers\API
 */

class SegmentAPIController extends BaseController
{
    /** @var  segmentRepository */
    private $segmentRepository;

    public function __construct(segmentRepository $segmentRepo)
    {
        $this->segmentRepository = $segmentRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    
    /**
     * Display a listing of the segment.
     * GET|HEAD /segments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->segmentRepository->pushCriteria(new RequestCriteria($request));
        $this->segmentRepository->pushCriteria(new LimitOffsetCriteria($request));
        $segments = $this->segmentRepository->all();

        return $this->sendResponse($segments->toArray(), 'Segments retrieved successfully');
    }

    /**
     * Store a newly created segment in storage.
     * POST /segments
     *
     * @param CreatesegmentAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatesegmentAPIRequest $request)
    {
        $input = $request->all();

        $segments = $this->segmentRepository->create($input);

        return $this->sendResponse($segments->toArray(), 'Segment saved successfully');
    }

    /**
     * Display the specified segment.
     * GET|HEAD /segments/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var segment $segment */
        $segment = $this->segmentRepository->findWithoutFail($id);

        if (empty($segment)) {
            return $this->sendError('Segment not found');
        }

        return $this->sendResponse($segment->toArray(), 'Segment retrieved successfully');
    }

    /**
     * Update the specified segment in storage.
     * PUT/PATCH /segments/{id}
     *
     * @param  int $id
     * @param UpdatesegmentAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatesegmentAPIRequest $request)
    {
        $input = $request->all();

        /** @var segment $segment */
        $segment = $this->segmentRepository->findWithoutFail($id);

        if (empty($segment)) {
            return $this->sendError('Segment not found');
        }

        $segment = $this->segmentRepository->update($input, $id);

        return $this->sendResponse($segment->toArray(), 'segment updated successfully');
    }

    /**
     * Remove the specified segment from storage.
     * DELETE /segments/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var segment $segment */
        $segment = $this->segmentRepository->findWithoutFail($id);

        if (empty($segment)) {
            return $this->sendError('Segment not found');
        }

        $segment->delete();

        return $this->sendResponse($id, 'Segment deleted successfully');
    }
}
