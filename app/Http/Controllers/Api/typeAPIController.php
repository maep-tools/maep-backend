<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatetypeAPIRequest;
use App\Http\Requests\API\UpdatetypeAPIRequest;
use App\Models\type;
use App\Repositories\typeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class typeController
 * @package App\Http\Controllers\API
 */

class typeAPIController extends BaseController
{
    /** @var  typeRepository */
    private $typeRepository;

    public function __construct(typeRepository $typeRepo)
    {
        $this->typeRepository = $typeRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }
    
    /**
     * Display a listing of the type.
     * GET|HEAD /types
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->typeRepository->pushCriteria(new RequestCriteria($request));
        $this->typeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $types = $this->typeRepository->all();

        return $this->sendResponse($types->toArray(), 'Types retrieved successfully');
    }

    /**
     * Store a newly created type in storage.
     * POST /types
     *
     * @param CreatetypeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatetypeAPIRequest $request)
    {
        $input = $request->all();

        $types = $this->typeRepository->create($input);

        return $this->sendResponse($types->toArray(), 'Type saved successfully');
    }

    /**
     * Display the specified type.
     * GET|HEAD /types/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var type $type */
        $type = $this->typeRepository->findWithoutFail($id);

        if (empty($type)) {
            return $this->sendError('Type not found');
        }

        return $this->sendResponse($type->toArray(), 'Type retrieved successfully');
    }

    /**
     * Update the specified type in storage.
     * PUT/PATCH /types/{id}
     *
     * @param  int $id
     * @param UpdatetypeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatetypeAPIRequest $request)
    {
        $input = $request->all();

        /** @var type $type */
        $type = $this->typeRepository->findWithoutFail($id);

        if (empty($type)) {
            return $this->sendError('Type not found');
        }

        $type = $this->typeRepository->update($input, $id);

        return $this->sendResponse($type->toArray(), 'type updated successfully');
    }

    /**
     * Remove the specified type from storage.
     * DELETE /types/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var type $type */
        $type = $this->typeRepository->findWithoutFail($id);

        if (empty($type)) {
            return $this->sendError('Type not found');
        }

        $type->delete();

        return $this->sendResponse($id, 'Type deleted successfully');
    }
}
