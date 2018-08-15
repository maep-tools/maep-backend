<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatescenarioAPIRequest;
use App\Http\Requests\API\UpdatescenarioAPIRequest;
use App\Models\scenario;
use App\Repositories\scenarioRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class scenarioController
 * @package App\Http\Controllers\API
 */

class scenarioAPIController extends BaseController
{
    /** @var  scenarioRepository */
    private $scenarioRepository;

    public function __construct(scenarioRepository $scenarioRepo)
    {
        $this->scenarioRepository = $scenarioRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the scenario.
     * GET|HEAD /scenarios
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->scenarioRepository->pushCriteria(new RequestCriteria($request));
        $this->scenarioRepository->pushCriteria(new LimitOffsetCriteria($request));
        $scenarios = $this->scenarioRepository->all();

        return $this->sendResponse($scenarios->toArray(), 'Scenarios retrieved successfully');
    }

    /**
     * Store a newly created scenario in storage.
     * POST /scenarios
     *
     * @param CreatescenarioAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatescenarioAPIRequest $request)
    {
        $input = $request->all();

        $scenarios = $this->scenarioRepository->create($input);

        return $this->sendResponse($scenarios->toArray(), 'Scenario saved successfully');
    }

    /**
     * Display the specified scenario.
     * GET|HEAD /scenarios/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var scenario $scenario */
        $scenario = $this->scenarioRepository->findWithoutFail($id);

        if (empty($scenario)) {
            return $this->sendResponse('Scenario not found');
        }

        return $this->sendResponse($scenario->toArray(), 'Scenario retrieved successfully');
    }

    /**
     * Update the specified scenario in storage.
     * PUT/PATCH /scenarios/{id}
     *
     * @param  int $id
     * @param UpdatescenarioAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatescenarioAPIRequest $request)
    {
        $input = $request->all();

        /** @var scenario $scenario */
        $scenario = $this->scenarioRepository->findWithoutFail($id);

        if (empty($scenario)) {
            return $this->sendResponse('Scenario not found');
        }

        $scenario = $this->scenarioRepository->update($input, $id);

        return $this->sendResponse($scenario->toArray(), 'scenario updated successfully');
    }

    /**
     * Remove the specified scenario from storage.
     * DELETE /scenarios/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var scenario $scenario */
        $scenario = $this->scenarioRepository->findWithoutFail($id);

        if (empty($scenario)) {
            return $this->sendResponse('Scenario not found');
        }

        $scenario->delete();

        return $this->sendResponse($id, 'Scenario deleted successfully');
    }
}
