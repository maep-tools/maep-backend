<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateinflowWindM2APIRequest;
use App\Http\Requests\API\UpdateinflowWindM2APIRequest;
use App\Models\inflowWindM2;
use App\Repositories\inflowWindM2Repository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;

/**
 * Class inflowWindM2Controller
 * @package App\Http\Controllers\API
 */

class inflowWindM2APIController extends BaseController
{
    /** @var  inflowWindM2Repository */
    private $inflowWindM2Repository;

    public function __construct(inflowWindM2Repository $inflowWindM2Repo)
    {
        $this->inflowWindM2Repository = $inflowWindM2Repo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }


    /**
     * Display a listing of the inflowWindM2.
     * GET|HEAD /inflowWindM2s
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->inflowWindM2Repository->pushCriteria(new RequestCriteria($request));
        $this->inflowWindM2Repository->pushCriteria(new LimitOffsetCriteria($request));
        $inflowWindM2s = $this->inflowWindM2Repository->all();

        return $this->sendResponse($inflowWindM2s->toArray(), 'Inflow Wind M2S retrieved successfully');
    }

    /**
     * Store a newly created inflowWindM2 in storage.
     * POST /inflowWindM2s
     *
     * @param CreateinflowWindM2APIRequest $request
     *
     * @return Response
     */
    public function store(CreateinflowWindM2APIRequest $request)
    {
        $input = $request->all();

        $inflowWindM2s = $this->inflowWindM2Repository->create($input);

        return $this->sendResponse($inflowWindM2s->toArray(), 'Inflow Wind M2 saved successfully');
    }

    /**
     * Display the specified inflowWindM2.
     * GET|HEAD /inflowWindM2s/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var inflowWindM2 $inflowWindM2 */
        $inflowWindM2 = $this->inflowWindM2Repository->findWithoutFail($id);

        if (empty($inflowWindM2)) {
            return $this->sendResponse('Inflow Wind M2 not found');
        }

        return $this->sendResponse($inflowWindM2->toArray(), 'Inflow Wind M2 retrieved successfully');
    }

    /**
     * Update the specified inflowWindM2 in storage.
     * PUT/PATCH /inflowWindM2s/{id}
     *
     * @param  int $id
     * @param UpdateinflowWindM2APIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateinflowWindM2APIRequest $request)
    {
        $input = $request->all();

        /** @var inflowWindM2 $inflowWindM2 */
        $inflowWindM2 = $this->inflowWindM2Repository->findWithoutFail($id);

        if (empty($inflowWindM2)) {
            return $this->sendResponse('Inflow Wind M2 not found');
        }

        $inflowWindM2 = $this->inflowWindM2Repository->update($input, $id);

        return $this->sendResponse($inflowWindM2->toArray(), 'inflowWindM2 updated successfully');
    }

    /**
     * Remove the specified inflowWindM2 from storage.
     * DELETE /inflowWindM2s/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var inflowWindM2 $inflowWindM2 */
        $inflowWindM2 = $this->inflowWindM2Repository->findWithoutFail($id);

        if (empty($inflowWindM2)) {
            return $this->sendResponse('Inflow Wind M2 not found');
        }

        $inflowWindM2->delete();

        return $this->sendResponse($id, 'Inflow Wind M2 deleted successfully');
    }
}
