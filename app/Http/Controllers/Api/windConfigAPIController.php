<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatewindConfigAPIRequest;
use App\Http\Requests\API\UpdatewindConfigAPIRequest;
use App\Models\windConfig;
use App\Repositories\windConfigRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;


/**
 * Class windConfigController
 * @package App\Http\Controllers\API
 */

class windConfigAPIController extends BaseController
{
    /** @var  windConfigRepository */
    private $windConfigRepository;

    public function __construct(windConfigRepository $windConfigRepo)
    {
        $this->windConfigRepository = $windConfigRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    
    /**
     * Display a listing of the windConfig.
     * GET|HEAD /windConfigs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->windConfigRepository->pushCriteria(new RequestCriteria($request));
        $this->windConfigRepository->pushCriteria(new LimitOffsetCriteria($request));
        $windConfigs = $this->windConfigRepository->all();

        return $this->sendResponse($windConfigs->toArray(), 'Wind Configs retrieved successfully');
    }

    /**
     * Store a newly created windConfig in storage.
     * POST /windConfigs
     *
     * @param CreatewindConfigAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatewindConfigAPIRequest $request)
    {
        $input = $request->all();

        $windConfigs = $this->windConfigRepository->create($input);

        return $this->sendResponse($windConfigs->toArray(), 'Wind Config saved successfully');
    }

    /**
     * Display the specified windConfig.
     * GET|HEAD /windConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var windConfig $windConfig */
        $windConfig = $this->windConfigRepository->findWithoutFail($id);

        if (empty($windConfig)) {
            return $this->sendResponse('Wind Config not found');
        }

        return $this->sendResponse($windConfig->toArray(), 'Wind Config retrieved successfully');
    }

    /**
     * Update the specified windConfig in storage.
     * PUT/PATCH /windConfigs/{id}
     *
     * @param  int $id
     * @param UpdatewindConfigAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatewindConfigAPIRequest $request)
    {
        $input = $request->all();

        /** @var windConfig $windConfig */
        $windConfig = $this->windConfigRepository->findWithoutFail($id);

        if (empty($windConfig)) {
            return $this->sendResponse('Wind Config not found');
        }

        $windConfig = $this->windConfigRepository->update($input, $id);

        return $this->sendResponse($windConfig->toArray(), 'windConfig updated successfully');
    }

    /**
     * Remove the specified windConfig from storage.
     * DELETE /windConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var windConfig $windConfig */
        $windConfig = $this->windConfigRepository->findWithoutFail($id);

        if (empty($windConfig)) {
            return $this->sendResponse('Wind Config not found');
        }

        $windConfig->delete();

        return $this->sendResponse($id, 'Wind Config deleted successfully');
    }
}
