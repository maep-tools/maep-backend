<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatehorizontAPIRequest;
use App\Http\Requests\API\UpdatehorizontAPIRequest;
use App\Models\horizont;
use App\Repositories\horizontRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use DB;

/**
 * Class horizontController
 * @package App\Http\Controllers\API
 */

class horizontAPIController extends BaseController
{
    /** @var  horizontRepository */
    private $horizontRepository;

    public function __construct(horizontRepository $horizontRepo)
    {
        $this->horizontRepository = $horizontRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the horizont.
     * GET|HEAD /horizonts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->horizontRepository->pushCriteria(new RequestCriteria($request));
        $this->horizontRepository->pushCriteria(new LimitOffsetCriteria($request));
        $horizonts = $this->horizontRepository->all();

        return $this->sendResponse($horizonts->toArray(), 'Horizonts retrieved successfully');
    }

    /**
     * Store a newly created horizont in storage.
     * POST /horizonts
     *
     * @param CreatehorizontAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatehorizontAPIRequest $request)
    {
        $input = $request->all();

        $input["period"] = Carbon::parse($input["period"]);
        $horizonts = $this->horizontRepository->create($input);

        return $this->sendResponse($horizonts->toArray(), 'Horizont saved successfully');
    }

    /**
     * Display the specified horizont.
     * GET|HEAD /horizonts/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var horizont $horizont */
        $horizont = $this->horizontRepository->findWithoutFail($id);

        if (empty($horizont)) {
            return $this->sendResponse('Horizont not found');
        }

        return $this->sendResponse($horizont->toArray(), 'Horizont retrieved successfully');
    }

    /**
     * Update the specified horizont in storage.
     * PUT/PATCH /horizonts/{id}
     *
     * @param  int $id
     * @param UpdatehorizontAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatehorizontAPIRequest $request)
    {
        $input = $request->all();

        /** @var horizont $horizont */
        $horizont = $this->horizontRepository->findWithoutFail($id);

        if (empty($horizont)) {
            return $this->sendResponse('Horizont not found');
        }

        $horizont = $this->horizontRepository->update($input, $id);

        return $this->sendResponse($horizont->toArray(), 'horizont updated successfully');
    }

    /**
     * Remove the specified horizont from storage.
     * DELETE /horizonts/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            /** @var horizont $horizont */
            $horizont = $this->horizontRepository->findWithoutFail($id);

            if (empty($horizont)) {
                return $this->sendResponse('error');
            }

            $horizont->delete();

            return $this->sendResponse($id, 'Horizont deleted successfully');   
        } catch (\Exception $exception) {
             return $this->sendResponse('error'); 
        }
    }


    public function deleteByProcessId($id) {
        try {
            DB::table('horizonts')->where('process_id', $id)->delete();
            return $this->sendResponse($id, 'Horizont deleted successfully');             
        } catch (\Exception $exception) {
             return $this->sendResponse('error');
        }      
    }
}
