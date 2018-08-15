<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatestorageExpansionAPIRequest;
use App\Http\Requests\API\UpdatestorageExpansionAPIRequest;
use App\Models\storageExpansion;
use App\Repositories\storageExpansionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Carbon\Carbon;

/**
 * Class storageExpansionController
 * @package App\Http\Controllers\API
 */

class storageExpansionAPIController extends BaseController
{
    /** @var  storageExpansionRepository */
    private $storageExpansionRepository;

    public function __construct(storageExpansionRepository $storageExpansionRepo)
    {
        $this->storageExpansionRepository = $storageExpansionRepo;
    }


    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the storageExpansion.
     * GET|HEAD /storageExpansions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->storageExpansionRepository->pushCriteria(new RequestCriteria($request));
        $this->storageExpansionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $storageExpansions = $this->storageExpansionRepository->all();

        return $this->sendResponse($storageExpansions->toArray(), 'Storage Expansions retrieved successfully');
    }

    /**
     * Store a newly created storageExpansion in storage.
     * POST /storageExpansions
     *
     * @param CreatestorageExpansionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatestorageExpansionAPIRequest $request)
    {
        $input = $request->all();        $storageExpansions = $this->storageExpansionRepository->create($input);

        return $this->sendResponse($storageExpansions->toArray(), 'Storage Expansion saved successfully');
    }

    /**
     * Display the specified storageExpansion.
     * GET|HEAD /storageExpansions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var storageExpansion $storageExpansion */
        $storageExpansion = $this->storageExpansionRepository->findWithoutFail($id);

        if (empty($storageExpansion)) {
            return $this->sendError('Storage Expansion not found');
        }

        return $this->sendResponse($storageExpansion->toArray(), 'Storage Expansion retrieved successfully');
    }

    /**
     * Update the specified storageExpansion in storage.
     * PUT/PATCH /storageExpansions/{id}
     *
     * @param  int $id
     * @param UpdatestorageExpansionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatestorageExpansionAPIRequest $request)
    {
        $input = $request->all();
        /** @var storageExpansion $storageExpansion */
        $storageExpansion = $this->storageExpansionRepository->findWithoutFail($id);

        if (empty($storageExpansion)) {
            return $this->sendResponse('Storage Expansion not found');
        }

        $storageExpansion = $this->storageExpansionRepository->update($input, $id);

        return $this->sendResponse($storageExpansion->toArray(), 'storageExpansion updated successfully');
    }

    /**
     * Remove the specified storageExpansion from storage.
     * DELETE /storageExpansions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var storageExpansion $storageExpansion */
        $storageExpansion = $this->storageExpansionRepository->findWithoutFail($id);

        if (empty($storageExpansion)) {
            return $this->sendResponse('Storage Expansion not found');
        }

        $storageExpansion->delete();

        return $this->sendResponse($id, 'Storage Expansion deleted successfully');
    }
}
