<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatemonthAPIRequest;
use App\Http\Requests\API\UpdatemonthAPIRequest;
use App\Models\month;
use App\Repositories\monthRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;

/**
 * Class monthController
 * @package App\Http\Controllers\API
 */

class monthAPIController extends BaseController
{
    /** @var  monthRepository */
    private $monthRepository;

    public function __construct(monthRepository $monthRepo)
    {
        $this->monthRepository = $monthRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the month.
     * GET|HEAD /months
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->monthRepository->pushCriteria(new RequestCriteria($request));
        $this->monthRepository->pushCriteria(new LimitOffsetCriteria($request));
        $months = $this->monthRepository->all();

        return $this->sendResponse($months->toArray(), 'Months retrieved successfully');
    }

    /**
     * Store a newly created month in storage.
     * POST /months
     *
     * @param CreatemonthAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatemonthAPIRequest $request)
    {
        $input = $request->all();

        $months = $this->monthRepository->create($input);

        return $this->sendResponse($months->toArray(), 'Month saved successfully');
    }

    /**
     * Display the specified month.
     * GET|HEAD /months/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var month $month */
        $month = $this->monthRepository->findWithoutFail($id);

        if (empty($month)) {
            return $this->sendResponse('Month not found');
        }

        return $this->sendResponse($month->toArray(), 'Month retrieved successfully');
    }

    /**
     * Update the specified month in storage.
     * PUT/PATCH /months/{id}
     *
     * @param  int $id
     * @param UpdatemonthAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatemonthAPIRequest $request)
    {
        $input = $request->all();

        /** @var month $month */
        $month = $this->monthRepository->findWithoutFail($id);

        if (empty($month)) {
            return $this->sendResponse('Month not found');
        }

        $month = $this->monthRepository->update($input, $id);

        return $this->sendResponse($month->toArray(), 'month updated successfully');
    }

    /**
     * Remove the specified month from storage.
     * DELETE /months/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var month $month */
        $month = $this->monthRepository->findWithoutFail($id);

        if (empty($month)) {
            return $this->sendResponse('Month not found');
        }

        $month->delete();

        return $this->sendResponse($id, 'Month deleted successfully');
    }
}
