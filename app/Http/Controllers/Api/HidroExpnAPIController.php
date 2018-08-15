<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateHidroExpnAPIRequest;
use App\Http\Requests\API\UpdateHidroExpnAPIRequest;
use App\Models\HidroExpn;
use App\Repositories\HidroExpnRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Carbon\Carbon;
/**
 * Class HidroExpnController
 * @package App\Http\Controllers\API
 */

class HidroExpnAPIController extends BaseController
{
    /** @var  HidroExpnRepository */
    private $hidroExpnRepository;

    public function __construct(HidroExpnRepository $hidroExpnRepo)
    {
        $this->hidroExpnRepository = $hidroExpnRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the HidroExpn.
     * GET|HEAD /hidroExpns
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->hidroExpnRepository->pushCriteria(new RequestCriteria($request));
        $this->hidroExpnRepository->pushCriteria(new LimitOffsetCriteria($request));
        $hidroExpns = $this->hidroExpnRepository->all();

        return $this->sendResponse($hidroExpns->toArray(), 'Hidro Expns retrieved successfully');
    }

    /**
     * Store a newly created HidroExpn in storage.
     * POST /hidroExpns
     *
     * @param CreateHidroExpnAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateHidroExpnAPIRequest $request)
    {
        $input = $request->all();
        $hidroExpns = $this->hidroExpnRepository->create($input);

        return $this->sendResponse($hidroExpns->toArray(), 'Hidro Expn saved successfully');
    }

    /**
     * Display the specified HidroExpn.
     * GET|HEAD /hidroExpns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var HidroExpn $hidroExpn */
        $hidroExpn = $this->hidroExpnRepository->findWithoutFail($id);

        if (empty($hidroExpn)) {
            return $this->sendError('Hidro Expn not found');
        }

        return $this->sendResponse($hidroExpn->toArray(), 'Hidro Expn retrieved successfully');
    }

    /**
     * Update the specified HidroExpn in storage.
     * PUT/PATCH /hidroExpns/{id}
     *
     * @param  int $id
     * @param UpdateHidroExpnAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHidroExpnAPIRequest $request)
    {
        $input = $request->all();
        /** @var HidroExpn $hidroExpn */
        $hidroExpn = $this->hidroExpnRepository->findWithoutFail($id);

        if (empty($hidroExpn)) {
            return $this->sendError('Hidro Expn not found');
        }

        $hidroExpn = $this->hidroExpnRepository->update($input, $id);

        return $this->sendResponse($hidroExpn->toArray(), 'HidroExpn updated successfully');
    }

    /**
     * Remove the specified HidroExpn from storage.
     * DELETE /hidroExpns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var HidroExpn $hidroExpn */
        $hidroExpn = $this->hidroExpnRepository->findWithoutFail($id);

        if (empty($hidroExpn)) {
            return $this->sendError('Hidro Expn not found');
        }

        $hidroExpn->delete();

        return $this->sendResponse($id, 'Hidro Expn deleted successfully');
    }
}
