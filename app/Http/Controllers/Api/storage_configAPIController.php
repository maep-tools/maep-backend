<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Createstorage_configAPIRequest;
use App\Http\Requests\API\Updatestorage_configAPIRequest;
use App\Models\storage_config;
use App\Repositories\storage_configRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Carbon\Carbon;

/**
 * Class storage_configController
 * @package App\Http\Controllers\API
 */

class storage_configAPIController extends BaseController
{
    /** @var  storage_configRepository */
    private $storageConfigRepository;

    public function __construct(storage_configRepository $storageConfigRepo)
    {
        $this->storageConfigRepository = $storageConfigRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }


    /**
     * Display a listing of the storage_config.
     * GET|HEAD /storageConfigs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->storageConfigRepository->pushCriteria(new RequestCriteria($request));
        $this->storageConfigRepository->pushCriteria(new LimitOffsetCriteria($request));
        $storageConfigs = $this->storageConfigRepository->all();

        return $this->sendResponse($storageConfigs->toArray(), 'Storage Configs retrieved successfully');
    }

    /**
     * Store a newly created storage_config in storage.
     * POST /storageConfigs
     *
     * @param Createstorage_configAPIRequest $request
     *
     * @return Response
     */
    public function store(Createstorage_configAPIRequest $request)
    {
        $input = $request->all();

        $storageConfigs = $this->storageConfigRepository->create($input);

        return $this->sendResponse($storageConfigs->toArray(), 'Storage Config saved successfully');
    }

    /**
     * Display the specified storage_config.
     * GET|HEAD /storageConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var storage_config $storageConfig */
        $storageConfig = $this->storageConfigRepository->findWithoutFail($id);

        if (empty($storageConfig)) {
            return $this->sendResponse('Storage Config not found');
        }

        return $this->sendResponse($storageConfig->toArray(), 'Storage Config retrieved successfully');
    }

    /**
     * Update the specified storage_config in storage.
     * PUT/PATCH /storageConfigs/{id}
     *
     * @param  int $id
     * @param Updatestorage_configAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Updatestorage_configAPIRequest $request)
    {
        $input = $request->all();

        /** @var storage_config $storageConfig */
        $storageConfig = $this->storageConfigRepository->findWithoutFail($id);

        if (empty($storageConfig)) {
            return $this->sendResponse('Storage Config not found');
        }

        $storageConfig = $this->storageConfigRepository->update($input, $id);

        return $this->sendResponse($storageConfig->toArray(), 'storage_config updated successfully');
    }

    /**
     * Remove the specified storage_config from storage.
     * DELETE /storageConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var storage_config $storageConfig */
        $storageConfig = $this->storageConfigRepository->findWithoutFail($id);

        if (empty($storageConfig)) {
            return $this->sendResponse('Storage Config not found');
        }

        $storageConfig->delete();

        return $this->sendResponse($id, 'Storage Config deleted successfully');
    }
}
