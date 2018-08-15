<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\API\CreateWindM2ConfigAPIRequest;
use App\Http\Requests\API\UpdateWindM2ConfigAPIRequest;
use App\Models\WindM2Config;
use App\Repositories\WindM2ConfigRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Carbon\Carbon;

/**
 * Class WindM2ConfigController
 * @package App\Http\Controllers\API
 */

class WindM2ConfigAPIController extends BaseController
{
    /** @var  WindM2ConfigRepository */
    private $windM2ConfigRepository;

    public function __construct(WindM2ConfigRepository $windM2ConfigRepo)
    {
        $this->windM2ConfigRepository = $windM2ConfigRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the WindM2Config.
     * GET|HEAD /windM2Configs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->windM2ConfigRepository->pushCriteria(new RequestCriteria($request));
        $this->windM2ConfigRepository->pushCriteria(new LimitOffsetCriteria($request));
        $windM2Configs = $this->windM2ConfigRepository->all();

        return $this->sendResponse($windM2Configs->toArray(), 'Wind M2 Configs retrieved successfully');
    }

    /**
     * Store a newly created WindM2Config in storage.
     * POST /windM2Configs
     *
     * @param CreateWindM2ConfigAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateWindM2ConfigAPIRequest $request)
    {
        $input = $request->all();

        $windM2Configs = $this->windM2ConfigRepository->create($input);

        return $this->sendResponse($windM2Configs->toArray(), 'Wind M2 Config saved successfully');
    }

    /**
     * Display the specified WindM2Config.
     * GET|HEAD /windM2Configs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var WindM2Config $windM2Config */
        $windM2Config = $this->windM2ConfigRepository->findWithoutFail($id);

        if (empty($windM2Config)) {
            return $this->sendResponse('Wind M2 Config not found');
        }

        return $this->sendResponse($windM2Config->toArray(), 'Wind M2 Config retrieved successfully');
    }

    /**
     * Update the specified WindM2Config in storage.
     * PUT/PATCH /windM2Configs/{id}
     *
     * @param  int $id
     * @param UpdateWindM2ConfigAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWindM2ConfigAPIRequest $request)
    {
        $input = $request->all();

        /** @var WindM2Config $windM2Config */
        $windM2Config = $this->windM2ConfigRepository->findWithoutFail($id);

        if (empty($windM2Config)) {
            return $this->sendResponse('Wind M2 Config not found');
        }

        $windM2Config = $this->windM2ConfigRepository->update($input, $id);

        return $this->sendResponse($windM2Config->toArray(), 'WindM2Config updated successfully');
    }

    /**
     * Remove the specified WindM2Config from storage.
     * DELETE /windM2Configs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var WindM2Config $windM2Config */
        $windM2Config = $this->windM2ConfigRepository->findWithoutFail($id);

        if (empty($windM2Config)) {
            return $this->sendResponse('Wind M2 Config not found');
        }

        $windM2Config->delete();

        return $this->sendResponse($id, 'Wind M2 Config deleted successfully');
    }
}
