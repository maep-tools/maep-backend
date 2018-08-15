<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSmallConfigAPIRequest;
use App\Http\Requests\API\UpdateSmallConfigAPIRequest;
use App\Models\SmallConfig;
use App\Repositories\SmallConfigRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Carbon\Carbon;


/**
 * Class SmallConfigController
 * @package App\Http\Controllers\API
 */

class SmallConfigAPIController extends BaseController
{
    /** @var  SmallConfigRepository */
    private $smallConfigRepository;

    public function __construct(SmallConfigRepository $smallConfigRepo)
    {
        $this->smallConfigRepository = $smallConfigRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the SmallConfig.
     * GET|HEAD /smallConfigs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->smallConfigRepository->pushCriteria(new RequestCriteria($request));
        $this->smallConfigRepository->pushCriteria(new LimitOffsetCriteria($request));
        $smallConfigs = $this->smallConfigRepository->all();

        return $this->sendResponse($smallConfigs->toArray(), 'Small Configs retrieved successfully');
    }

    /**
     * Store a newly created SmallConfig in storage.
     * POST /smallConfigs
     *
     * @param CreateSmallConfigAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSmallConfigAPIRequest $request)
    {
        $input = $request->all();

        $smallConfigs = $this->smallConfigRepository->create($input);

        return $this->sendResponse($smallConfigs->toArray(), 'Small Config saved successfully');
    }

    /**
     * Display the specified SmallConfig.
     * GET|HEAD /smallConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SmallConfig $smallConfig */
        $smallConfig = $this->smallConfigRepository->findWithoutFail($id);

        if (empty($smallConfig)) {
            return $this->sendResponse('Small Config not found');
        }

        return $this->sendResponse($smallConfig->toArray(), 'Small Config retrieved successfully');
    }

    /**
     * Update the specified SmallConfig in storage.
     * PUT/PATCH /smallConfigs/{id}
     *
     * @param  int $id
     * @param UpdateSmallConfigAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSmallConfigAPIRequest $request)
    {
        $input = $request->all();
        
        /** @var SmallConfig $smallConfig */
        $smallConfig = $this->smallConfigRepository->findWithoutFail($id);

        if (empty($smallConfig)) {
            return $this->sendResponse('Small Config not found');
        }

        $smallConfig = $this->smallConfigRepository->update($input, $id);

        return $this->sendResponse($smallConfig->toArray(), 'SmallConfig updated successfully');
    }

    /**
     * Remove the specified SmallConfig from storage.
     * DELETE /smallConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SmallConfig $smallConfig */
        $smallConfig = $this->smallConfigRepository->findWithoutFail($id);

        if (empty($smallConfig)) {
            return $this->sendResponse('Small Config not found');
        }

        $smallConfig->delete();

        return $this->sendResponse($id, 'Small Config deleted successfully');
    }
}
