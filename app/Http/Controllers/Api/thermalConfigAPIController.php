<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatethermalConfigAPIRequest;
use App\Http\Requests\API\UpdatethermalConfigAPIRequest;
use App\Models\thermalConfig;
use App\Repositories\thermalConfigRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

/**
 * Class thermalConfigController
 * @package App\Http\Controllers\API
 */

class thermalConfigAPIController extends BaseController
{
    /** @var  thermalConfigRepository */
    private $thermalConfigRepository;

    public function __construct(thermalConfigRepository $thermalConfigRepo)
    {
        $this->thermalConfigRepository = $thermalConfigRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    
    /**
     * Display a listing of the thermalConfig.
     * GET|HEAD /thermalConfigs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->thermalConfigRepository->pushCriteria(new RequestCriteria($request));
        $this->thermalConfigRepository->pushCriteria(new LimitOffsetCriteria($request));
        $thermalConfigs = $this->thermalConfigRepository->all();

        return $this->sendResponse($thermalConfigs->toArray(), 'Thermal Configs retrieved successfully');
    }

    /**
     * Store a newly created thermalConfig in storage.
     * POST /thermalConfigs
     *
     * @param CreatethermalConfigAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatethermalConfigAPIRequest $request)
    {
        $input = $request->all();

        $thermalConfigs = $this->thermalConfigRepository->create($input);

        return $this->sendResponse($thermalConfigs->toArray(), 'Thermal Config saved successfully');
    }

    /**
     * Display the specified thermalConfig.
     * GET|HEAD /thermalConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var thermalConfig $thermalConfig */
        $thermalConfig = $this->thermalConfigRepository->findWithoutFail($id);

        if (empty($thermalConfig)) {
            return $this->sendResponse('Thermal Config not found');
        }

        return $this->sendResponse($thermalConfig->toArray(), 'Thermal Config retrieved successfully');
    }

    /**
     * Update the specified thermalConfig in storage.
     * PUT/PATCH /thermalConfigs/{id}
     *
     * @param  int $id
     * @param UpdatethermalConfigAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatethermalConfigAPIRequest $request)
    {
        $input = $request->all();

        /** @var thermalConfig $thermalConfig */
        $thermalConfig = $this->thermalConfigRepository->findWithoutFail($id);

        if (empty($thermalConfig)) {
            return $this->sendResponse('Thermal Config not found');
        }

        $thermalConfig = $this->thermalConfigRepository->update($input, $id);

        return $this->sendResponse($thermalConfig->toArray(), 'thermalConfig updated successfully');
    }

    /**
     * Remove the specified thermalConfig from storage.
     * DELETE /thermalConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var thermalConfig $thermalConfig */
        $thermalConfig = $this->thermalConfigRepository->findWithoutFail($id);

        if (empty($thermalConfig)) {
            return $this->sendResponse('Thermal Config not found');
        }

        $thermalConfig->delete();

        return $this->sendResponse($id, 'Thermal Config deleted successfully');
    }
}
