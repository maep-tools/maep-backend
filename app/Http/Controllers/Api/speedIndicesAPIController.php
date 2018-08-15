<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatespeedIndicesAPIRequest;
use App\Http\Requests\API\UpdatespeedIndicesAPIRequest;
use App\Models\speedIndices;
use App\Repositories\speedIndicesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use App\Models\Horizont;
use App\models\Month;

/**
 * Class speedIndicesController
 * @package App\Http\Controllers\API
 */

class speedIndicesAPIController extends BaseController
{
    /** @var  speedIndicesRepository */
    private $speedIndicesRepository;

    public function __construct(speedIndicesRepository $speedIndicesRepo)
    {
        $this->speedIndicesRepository = $speedIndicesRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the speedIndices.
     * GET|HEAD /speedIndices
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->speedIndicesRepository->pushCriteria(new RequestCriteria($request));
        $this->speedIndicesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $speedIndices = $this->speedIndicesRepository->all();

        return $this->sendResponse($speedIndices->toArray(), 'Speed Indices retrieved successfully');
    }

    /**
     * Store a newly created speedIndices in storage.
     * POST /speedIndices
     *
     * @param CreatespeedIndicesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatespeedIndicesAPIRequest $request)
    {
        $input = $request->all();

        $speedIndices = $this->speedIndicesRepository->create($input);

        return $this->sendResponse($speedIndices->toArray(), 'Speed Indices saved successfully');
    }

    /**
     * Display the specified speedIndices.
     * GET|HEAD /speedIndices/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var speedIndices $speedIndices */
        $speedIndices = $this->speedIndicesRepository->findWithoutFail($id);

        if (empty($speedIndices)) {
            return $this->sendResponse('Speed Indices not found');
        }

        return $this->sendResponse($speedIndices->toArray(), 'Speed Indices retrieved successfully');
    }

    /**
     * Update the specified speedIndices in storage.
     * PUT/PATCH /speedIndices/{id}
     *
     * @param  int $id
     * @param UpdatespeedIndicesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatespeedIndicesAPIRequest $request)
    {
        $input = $request->all();

        /** @var speedIndices $speedIndices */
        $speedIndices = $this->speedIndicesRepository->findWithoutFail($id);

        if (empty($speedIndices)) {
            return $this->sendResponse('Speed Indices not found');
        }

        $speedIndices = $this->speedIndicesRepository->update($input, $id);

        return $this->sendResponse($speedIndices->toArray(), 'speedIndices updated successfully');
    }

    /**
     * Remove the specified speedIndices from storage.
     * DELETE /speedIndices/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var speedIndices $speedIndices */
        $speedIndices = $this->speedIndicesRepository->findWithoutFail($id);

        if (empty($speedIndices)) {
            return $this->sendResponse('Speed Indices not found');
        }

        $speedIndices->delete();

        return $this->sendResponse($id, 'Speed Indices deleted successfully');
    }



    public function getSpeedIndicesByWindConfigId($id)  {
        return Month::leftJoin('speed_indices', 'speed_indices.month_id', '=', 'months.id')
            ->leftJoin('wind_configs', 'speed_indices.wind_config_id', '=', 'wind_configs.id')
            ->where('wind_configs.id', '=', $id)
            ->select('speed_indices.id', 'speed_indices.value', 'speed_indices.month_id', 'speed_indices.wind_config_id', 'speed_indices.month_id', 'speed_indices.block_id')
            ->get();         

    }

}
