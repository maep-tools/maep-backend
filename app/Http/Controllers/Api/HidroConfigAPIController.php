<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateHidroConfigAPIRequest;
use App\Http\Requests\API\UpdateHidroConfigAPIRequest;
use App\Models\HidroConfig;
use App\Repositories\HidroConfigRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Carbon\Carbon;

/**
 * Class HidroConfigController
 * @package App\Http\Controllers\API
 */

class HidroConfigAPIController extends BaseController
{
    /** @var  HidroConfigRepository */
    private $hidroConfigRepository;

    public function __construct(HidroConfigRepository $hidroConfigRepo)
    {
        $this->hidroConfigRepository = $hidroConfigRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the HidroConfig.
     * GET|HEAD /hidroConfigs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->hidroConfigRepository->pushCriteria(new RequestCriteria($request));
        $this->hidroConfigRepository->pushCriteria(new LimitOffsetCriteria($request));
        $hidroConfigs = $this->hidroConfigRepository->all();

        return $this->sendResponse($hidroConfigs->toArray(), 'Hidro Configs retrieved successfully');
    }

    /**
     * Store a newly created HidroConfig in storage.
     * POST /hidroConfigs
     *
     * @param CreateHidroConfigAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateHidroConfigAPIRequest $request)
    {
        $input = $request->all();

        $hidroConfigs = $this->hidroConfigRepository->create($input);

        return $this->sendResponse($hidroConfigs->toArray(), 'Hidro Config saved successfully');
    }

    /**
     * Display the specified HidroConfig.
     * GET|HEAD /hidroConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var HidroConfig $hidroConfig */
        $hidroConfig = $this->hidroConfigRepository->findWithoutFail($id);

        if (empty($hidroConfig)) {
            return $this->sendError('Hidro Config not found');
        }

        return $this->sendResponse($hidroConfig->toArray(), 'Hidro Config retrieved successfully');
    }

    /**
     * Update the specified HidroConfig in storage.
     * PUT/PATCH /hidroConfigs/{id}
     *
     * @param  int $id
     * @param UpdateHidroConfigAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHidroConfigAPIRequest $request)
    {
        $input = $request->all();
        /** @var HidroConfig $hidroConfig */
        $hidroConfig = $this->hidroConfigRepository->findWithoutFail($id);

        if (empty($hidroConfig)) {
            return $this->sendError('Hidro Config not found');
        }

        $hidroConfig = $this->hidroConfigRepository->update($input, $id);

        return $this->sendResponse($hidroConfig->toArray(), 'HidroConfig updated successfully');
    }

    /**
     * Remove the specified HidroConfig from storage.
     * DELETE /hidroConfigs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var HidroConfig $hidroConfig */
        $hidroConfig = $this->hidroConfigRepository->findWithoutFail($id);

        if (empty($hidroConfig)) {
            return $this->sendError('Hidro Config not found');
        }

        $hidroConfig->delete();

        return $this->sendResponse($id, 'Hidro Config deleted successfully');
    }
}
