<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFuelCostPlantAPIRequest;
use App\Http\Requests\API\UpdateFuelCostPlantAPIRequest;
use App\Models\FuelCostPlant;
use App\Repositories\FuelCostPlantRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;

/**
 * Class FuelCostPlantController
 * @package App\Http\Controllers\API
 */

class FuelCostPlantAPIController extends BaseController
{
    /** @var  FuelCostPlantRepository */
    private $fuelCostPlantRepository;

    public function __construct(FuelCostPlantRepository $fuelCostPlantRepo)
    {
        $this->fuelCostPlantRepository = $fuelCostPlantRepo;
    }


    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the FuelCostPlant.
     * GET|HEAD /fuelCostPlants
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->fuelCostPlantRepository->pushCriteria(new RequestCriteria($request));
        $this->fuelCostPlantRepository->pushCriteria(new LimitOffsetCriteria($request));
        $fuelCostPlants = $this->fuelCostPlantRepository->all();

        return $this->sendResponse($fuelCostPlants->toArray(), 'Fuel Cost Plants retrieved successfully');
    }

    /**
     * Store a newly created FuelCostPlant in storage.
     * POST /fuelCostPlants
     *
     * @param CreateFuelCostPlantAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFuelCostPlantAPIRequest $request)
    {
        $input = $request->all();

        $fuelCostPlants = $this->fuelCostPlantRepository->create($input);

        return $this->sendResponse($fuelCostPlants->toArray(), 'Fuel Cost Plant saved successfully');
    }

    /**
     * Display the specified FuelCostPlant.
     * GET|HEAD /fuelCostPlants/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FuelCostPlant $fuelCostPlant */
        $fuelCostPlant = $this->fuelCostPlantRepository->findWithoutFail($id);

        if (empty($fuelCostPlant)) {
            return $this->sendError('Fuel Cost Plant not found');
        }

        return $this->sendResponse($fuelCostPlant->toArray(), 'Fuel Cost Plant retrieved successfully');
    }

    /**
     * Update the specified FuelCostPlant in storage.
     * PUT/PATCH /fuelCostPlants/{id}
     *
     * @param  int $id
     * @param UpdateFuelCostPlantAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFuelCostPlantAPIRequest $request)
    {
        $input = $request->all();

        /** @var FuelCostPlant $fuelCostPlant */
        $fuelCostPlant = $this->fuelCostPlantRepository->findWithoutFail($id);

        if (empty($fuelCostPlant)) {
            return $this->sendError('Fuel Cost Plant not found');
        }

        $fuelCostPlant = $this->fuelCostPlantRepository->update($input, $id);

        return $this->sendResponse($fuelCostPlant->toArray(), 'FuelCostPlant updated successfully');
    }

    /**
     * Remove the specified FuelCostPlant from storage.
     * DELETE /fuelCostPlants/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FuelCostPlant $fuelCostPlant */
        $fuelCostPlant = $this->fuelCostPlantRepository->findWithoutFail($id);

        if (empty($fuelCostPlant)) {
            return $this->sendError('Fuel Cost Plant not found');
        }

        $fuelCostPlant->delete();

        return $this->sendResponse($id, 'Fuel Cost Plant deleted successfully');
    }
}
