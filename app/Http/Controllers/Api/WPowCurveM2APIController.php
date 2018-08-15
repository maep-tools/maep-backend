<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWPowCurveM2APIRequest;
use App\Http\Requests\API\UpdateWPowCurveM2APIRequest;
use App\Models\WPowCurveM2;
use App\Repositories\WPowCurveM2Repository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;



/**
 * Class WPowCurveM2Controller
 * @package App\Http\Controllers\API
 */

class WPowCurveM2APIController extends BaseController
{
    /** @var  WPowCurveM2Repository */
    private $wPowCurveM2Repository;

    public function __construct(WPowCurveM2Repository $wPowCurveM2Repo)
    {
        $this->wPowCurveM2Repository = $wPowCurveM2Repo;
    }

    public function getWPowCurveByWindConfigM2Id($id) {
        return WPowCurveM2::where('wind_m2_config_id', '=', $id)->get();
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    /**
     * Display a listing of the WPowCurveM2.
     * GET|HEAD /wPowCurveM2s
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->wPowCurveM2Repository->pushCriteria(new RequestCriteria($request));
        $this->wPowCurveM2Repository->pushCriteria(new LimitOffsetCriteria($request));
        $wPowCurveM2s = $this->wPowCurveM2Repository->all();

        return $this->sendResponse($wPowCurveM2s->toArray(), 'W Pow Curve M2S retrieved successfully');
    }

    /**
     * Store a newly created WPowCurveM2 in storage.
     * POST /wPowCurveM2s
     *
     * @param CreateWPowCurveM2APIRequest $request
     *
     * @return Response
     */
    public function store(CreateWPowCurveM2APIRequest $request)
    {
        $input = $request->all();

        $wPowCurveM2s = $this->wPowCurveM2Repository->create($input);

        return $this->sendResponse($wPowCurveM2s->toArray(), 'W Pow Curve M2 saved successfully');
    }

    /**
     * Display the specified WPowCurveM2.
     * GET|HEAD /wPowCurveM2s/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var WPowCurveM2 $wPowCurveM2 */
        $wPowCurveM2 = $this->wPowCurveM2Repository->findWithoutFail($id);

        if (empty($wPowCurveM2)) {
            return $this->sendResponse('W Pow Curve M2 not found');
        }

        return $this->sendResponse($wPowCurveM2->toArray(), 'W Pow Curve M2 retrieved successfully');
    }

    /**
     * Update the specified WPowCurveM2 in storage.
     * PUT/PATCH /wPowCurveM2s/{id}
     *
     * @param  int $id
     * @param UpdateWPowCurveM2APIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWPowCurveM2APIRequest $request)
    {
        $input = $request->all();

        /** @var WPowCurveM2 $wPowCurveM2 */
        $wPowCurveM2 = $this->wPowCurveM2Repository->findWithoutFail($id);

        if (empty($wPowCurveM2)) {
            return $this->sendResponse('W Pow Curve M2 not found');
        }

        $wPowCurveM2 = $this->wPowCurveM2Repository->update($input, $id);

        return $this->sendResponse($wPowCurveM2->toArray(), 'WPowCurveM2 updated successfully');
    }

    /**
     * Remove the specified WPowCurveM2 from storage.
     * DELETE /wPowCurveM2s/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var WPowCurveM2 $wPowCurveM2 */
        $wPowCurveM2 = $this->wPowCurveM2Repository->findWithoutFail($id);

        if (empty($wPowCurveM2)) {
            return $this->sendResponse('W Pow Curve M2 not found');
        }

        $wPowCurveM2->delete();

        return $this->sendResponse($id, 'W Pow Curve M2 deleted successfully');
    }
}
