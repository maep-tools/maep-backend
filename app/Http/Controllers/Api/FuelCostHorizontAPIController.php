<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFuelCostHorizontAPIRequest;
use App\Http\Requests\API\UpdateFuelCostHorizontAPIRequest;
use App\Models\FuelCostHorizont;
use App\Repositories\FuelCostHorizontRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use App\Models\Horizont;

/**
 * Class FuelCostHorizontController
 * @package App\Http\Controllers\API
 */

class FuelCostHorizontAPIController extends BaseController
{
    /** @var  FuelCostHorizontRepository */
    private $fuelCostHorizontRepository;

    public function __construct(FuelCostHorizontRepository $fuelCostHorizontRepo)
    {
        $this->fuelCostHorizontRepository = $fuelCostHorizontRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    
    /**
     * Display a listing of the FuelCostHorizont.
     * GET|HEAD /fuelCostHorizonts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->fuelCostHorizontRepository->pushCriteria(new RequestCriteria($request));
        $this->fuelCostHorizontRepository->pushCriteria(new LimitOffsetCriteria($request));
        $fuelCostHorizonts = $this->fuelCostHorizontRepository->all();

        return $this->sendResponse($fuelCostHorizonts->toArray(), 'Fuel Cost Horizonts retrieved successfully');
    }

    /**
     * Store a newly created FuelCostHorizont in storage.
     * POST /fuelCostHorizonts
     *
     * @param CreateFuelCostHorizontAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFuelCostHorizontAPIRequest $request)
    {
        $input = $request->all();

        $fuelCostHorizonts = $this->fuelCostHorizontRepository->create($input);

        return $this->sendResponse($fuelCostHorizonts->toArray(), 'Fuel Cost Horizont saved successfully');
    }

    /**
     * Display the specified FuelCostHorizont.
     * GET|HEAD /fuelCostHorizonts/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FuelCostHorizont $fuelCostHorizont */
        $fuelCostHorizont = $this->fuelCostHorizontRepository->findWithoutFail($id);

        if (empty($fuelCostHorizont)) {
            return $this->sendResponse('Fuel Cost Horizont not found');
        }

        return $this->sendResponse($fuelCostHorizont->toArray(), 'Fuel Cost Horizont retrieved successfully');
    }

    /**
     * Update the specified FuelCostHorizont in storage.
     * PUT/PATCH /fuelCostHorizonts/{id}
     *
     * @param  int $id
     * @param UpdateFuelCostHorizontAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFuelCostHorizontAPIRequest $request)
    {
        $input = $request->all();

        /** @var FuelCostHorizont $fuelCostHorizont */
        $fuelCostHorizont = $this->fuelCostHorizontRepository->findWithoutFail($id);

        if (empty($fuelCostHorizont)) {
            return $this->sendResponse('Fuel Cost Horizont not found');
        }

        $fuelCostHorizont = $this->fuelCostHorizontRepository->update($input, $id);

        return $this->sendResponse($fuelCostHorizont->toArray(), 'FuelCostHorizont updated successfully');
    }

    /**
     * Remove the specified FuelCostHorizont from storage.
     * DELETE /fuelCostHorizonts/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FuelCostHorizont $fuelCostHorizont */
        $fuelCostHorizont = $this->fuelCostHorizontRepository->findWithoutFail($id);

        if (empty($fuelCostHorizont)) {
            return $this->sendResponse('Fuel Cost Horizont not found');
        }

        $fuelCostHorizont->delete();

        return $this->sendResponse($id, 'Fuel Cost Horizont deleted successfully');
    }


    public function getFuelCostHorizontsByFuelId ($id) {
        try {
        return Horizont::leftJoin('fuel_cost_horizonts', 'fuel_cost_horizonts.horizont_id', '=', 'horizonts.id')
        ->leftJoin('fuel_cost_plants', 'fuel_cost_horizonts.planta_fuel_id', '=', 'fuel_cost_plants.id')
        ->where('fuel_cost_plants.id', '=', $id)
        ->select('fuel_cost_horizonts.id', 'fuel_cost_horizonts.value', 'fuel_cost_horizonts.horizont_id', 'fuel_cost_horizonts.planta_fuel_id')
        ->get();         
        } catch (Exception $e) {
            return $e;
        }
    }

}
