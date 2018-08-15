<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateThermal_expnAPIRequest;
use App\Http\Requests\API\UpdateThermal_expnAPIRequest;
use App\Models\Thermal_expn;
use App\Repositories\Thermal_expnRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Carbon\Carbon;

/**
 * Class Thermal_expnController
 * @package App\Http\Controllers\API
 */

class Thermal_expnAPIController extends BaseController
{
    /** @var  Thermal_expnRepository */
    private $thermalExpnRepository;

    public function __construct(Thermal_expnRepository $thermalExpnRepo)
    {
        $this->thermalExpnRepository = $thermalExpnRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    
    /**
     * Display a listing of the Thermal_expn.
     * GET|HEAD /thermalExpns
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->thermalExpnRepository->pushCriteria(new RequestCriteria($request));
        $this->thermalExpnRepository->pushCriteria(new LimitOffsetCriteria($request));
        $thermalExpns = $this->thermalExpnRepository->all();

        return $this->sendResponse($thermalExpns->toArray(), 'Thermal Expns retrieved successfully');
    }

    /**
     * Store a newly created Thermal_expn in storage.
     * POST /thermalExpns
     *
     * @param CreateThermal_expnAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateThermal_expnAPIRequest $request)
    {
        $input = $request->all();
        $thermalExpns = $this->thermalExpnRepository->create($input);

        return $this->sendResponse($thermalExpns->toArray(), 'Thermal Expn saved successfully');
    }

    /**
     * Display the specified Thermal_expn.
     * GET|HEAD /thermalExpns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Thermal_expn $thermalExpn */
        $thermalExpn = $this->thermalExpnRepository->findWithoutFail($id);

        if (empty($thermalExpn)) {
            return $this->sendError('Thermal Expn not found');
        }

        return $this->sendResponse($thermalExpn->toArray(), 'Thermal Expn retrieved successfully');
    }

    /**
     * Update the specified Thermal_expn in storage.
     * PUT/PATCH /thermalExpns/{id}
     *
     * @param  int $id
     * @param UpdateThermal_expnAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateThermal_expnAPIRequest $request)
    {
        $input = $request->all();
        /** @var Thermal_expn $thermalExpn */
        $thermalExpn = $this->thermalExpnRepository->findWithoutFail($id);

        if (empty($thermalExpn)) {
            return $this->sendError('Thermal Expn not found');
        }

        $thermalExpn = $this->thermalExpnRepository->update($input, $id);

        return $this->sendResponse($thermalExpn->toArray(), 'Thermal_expn updated successfully');
    }

    /**
     * Remove the specified Thermal_expn from storage.
     * DELETE /thermalExpns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Thermal_expn $thermalExpn */
        $thermalExpn = $this->thermalExpnRepository->findWithoutFail($id);

        if (empty($thermalExpn)) {
            return $this->sendError('Thermal Expn not found');
        }

        $thermalExpn->delete();

        return $this->sendResponse($id, 'Thermal Expn deleted successfully');
    }
}
