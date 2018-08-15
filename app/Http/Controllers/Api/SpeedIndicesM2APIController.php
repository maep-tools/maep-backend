<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSpeedIndicesM2APIRequest;
use App\Http\Requests\API\UpdateSpeedIndicesM2APIRequest;
use App\Models\SpeedIndicesM2;
use App\Repositories\SpeedIndicesM2Repository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Month;

/**
 * Class SpeedIndicesM2Controller
 * @package App\Http\Controllers\API
 */

class SpeedIndicesM2APIController extends BaseController
{
    /** @var  SpeedIndicesM2Repository */
    private $speedIndicesM2Repository;

    public function __construct(SpeedIndicesM2Repository $speedIndicesM2Repo)
    {
        $this->speedIndicesM2Repository = $speedIndicesM2Repo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the SpeedIndicesM2.
     * GET|HEAD /speedIndicesM2s
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->speedIndicesM2Repository->pushCriteria(new RequestCriteria($request));
        $this->speedIndicesM2Repository->pushCriteria(new LimitOffsetCriteria($request));
        $speedIndicesM2s = $this->speedIndicesM2Repository->all();

        return $this->sendResponse($speedIndicesM2s->toArray(), 'Speed Indices M2S retrieved successfully');
    }

    /**
     * Store a newly created SpeedIndicesM2 in storage.
     * POST /speedIndicesM2s
     *
     * @param CreateSpeedIndicesM2APIRequest $request
     *
     * @return Response
     */
    public function store(CreateSpeedIndicesM2APIRequest $request)
    {
        $input = $request->all();

        $speedIndicesM2s = $this->speedIndicesM2Repository->create($input);

        return $this->sendResponse($speedIndicesM2s->toArray(), 'Speed Indices M2 saved successfully');
    }

    /**
     * Display the specified SpeedIndicesM2.
     * GET|HEAD /speedIndicesM2s/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SpeedIndicesM2 $speedIndicesM2 */
        $speedIndicesM2 = $this->speedIndicesM2Repository->findWithoutFail($id);

        if (empty($speedIndicesM2)) {
            return $this->sendResponse('Speed Indices M2 not found');
        }

        return $this->sendResponse($speedIndicesM2->toArray(), 'Speed Indices M2 retrieved successfully');
    }

    /**
     * Update the specified SpeedIndicesM2 in storage.
     * PUT/PATCH /speedIndicesM2s/{id}
     *
     * @param  int $id
     * @param UpdateSpeedIndicesM2APIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSpeedIndicesM2APIRequest $request)
    {
        $input = $request->all();

        /** @var SpeedIndicesM2 $speedIndicesM2 */
        $speedIndicesM2 = $this->speedIndicesM2Repository->findWithoutFail($id);

        if (empty($speedIndicesM2)) {
            return $this->sendResponse('Speed Indices M2 not found');
        }

        $speedIndicesM2 = $this->speedIndicesM2Repository->update($input, $id);

        return $this->sendResponse($speedIndicesM2->toArray(), 'SpeedIndicesM2 updated successfully');
    }

    /**
     * Remove the specified SpeedIndicesM2 from storage.
     * DELETE /speedIndicesM2s/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SpeedIndicesM2 $speedIndicesM2 */
        $speedIndicesM2 = $this->speedIndicesM2Repository->findWithoutFail($id);

        if (empty($speedIndicesM2)) {
            return $this->sendResponse('Speed Indices M2 not found');
        }

        $speedIndicesM2->delete();

        return $this->sendResponse($id, 'Speed Indices M2 deleted successfully');
    }


    public function getSpeedIndicesByWindConfigM2Id($id)  {
        return Month::leftJoin('speed_indices_m2s', 'speed_indices_m2s.month_id', '=', 'months.id')
            ->leftJoin('wind_m2_configs', 'speed_indices_m2s.wind_config_id', '=', 'wind_m2_configs.id')
            ->where('wind_m2_configs.id', '=', $id)
            ->select('speed_indices_m2s.id', 'speed_indices_m2s.value', 'speed_indices_m2s.month_id', 'speed_indices_m2s.wind_config_id', 'speed_indices_m2s.month_id', 'speed_indices_m2s.block_id')
            ->get();         

    }
}
