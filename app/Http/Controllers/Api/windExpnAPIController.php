<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatewindExpnAPIRequest;
use App\Http\Requests\API\UpdatewindExpnAPIRequest;
use App\Models\windExpn;
use App\Repositories\windExpnRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

/**
 * Class windExpnController
 * @package App\Http\Controllers\API
 */

class windExpnAPIController extends BaseController
{
    /** @var  windExpnRepository */
    private $windExpnRepository;

    public function __construct(windExpnRepository $windExpnRepo)
    {
        $this->windExpnRepository = $windExpnRepo;
    }


    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the windExpn.
     * GET|HEAD /windExpns
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->windExpnRepository->pushCriteria(new RequestCriteria($request));
        $this->windExpnRepository->pushCriteria(new LimitOffsetCriteria($request));
        $windExpns = $this->windExpnRepository->all();

        return $this->sendResponse($windExpns->toArray(), 'Wind Expns retrieved successfully');
    }

    /**
     * Store a newly created windExpn in storage.
     * POST /windExpns
     *
     * @param CreatewindExpnAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatewindExpnAPIRequest $request)
    {
        $input = $request->all();

        $windExpns = $this->windExpnRepository->create($input);

        return $this->sendResponse($windExpns->toArray(), 'Wind Expn saved successfully');
    }

    /**
     * Display the specified windExpn.
     * GET|HEAD /windExpns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var windExpn $windExpn */
        $windExpn = $this->windExpnRepository->findWithoutFail($id);

        if (empty($windExpn)) {
            return $this->sendError('Wind Expn not found');
        }

        return $this->sendResponse($windExpn->toArray(), 'Wind Expn retrieved successfully');
    }

    /**
     * Update the specified windExpn in storage.
     * PUT/PATCH /windExpns/{id}
     *
     * @param  int $id
     * @param UpdatewindExpnAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatewindExpnAPIRequest $request)
    {
        $input = $request->all();

        /** @var windExpn $windExpn */
        $windExpn = $this->windExpnRepository->findWithoutFail($id);

        if (empty($windExpn)) {
            return $this->sendError('Wind Expn not found');
        }

        $windExpn = $this->windExpnRepository->update($input, $id);

        return $this->sendResponse($windExpn->toArray(), 'windExpn updated successfully');
    }

    /**
     * Remove the specified windExpn from storage.
     * DELETE /windExpns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var windExpn $windExpn */
        $windExpn = $this->windExpnRepository->findWithoutFail($id);

        if (empty($windExpn)) {
            return $this->sendError('Wind Expn not found');
        }

        $windExpn->delete();

        return $this->sendResponse($id, 'Wind Expn deleted successfully');
    }
}
