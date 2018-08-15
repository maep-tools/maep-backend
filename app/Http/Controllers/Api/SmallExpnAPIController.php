<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSmallExpnAPIRequest;
use App\Http\Requests\API\UpdateSmallExpnAPIRequest;
use App\Models\SmallExpn;
use App\Repositories\SmallExpnRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
/**
 * Class SmallExpnController
 * @package App\Http\Controllers\API
 */

class SmallExpnAPIController extends BaseController
{
    /** @var  SmallExpnRepository */
    private $smallExpnRepository;

    public function __construct(SmallExpnRepository $smallExpnRepo)
    {
        $this->smallExpnRepository = $smallExpnRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the SmallExpn.
     * GET|HEAD /smallExpns
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->smallExpnRepository->pushCriteria(new RequestCriteria($request));
        $this->smallExpnRepository->pushCriteria(new LimitOffsetCriteria($request));
        $smallExpns = $this->smallExpnRepository->all();

        return $this->sendResponse($smallExpns->toArray(), 'Small Expns retrieved successfully');
    }

    /**
     * Store a newly created SmallExpn in storage.
     * POST /smallExpns
     *
     * @param CreateSmallExpnAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSmallExpnAPIRequest $request)
    {
        $input = $request->all();
        $smallExpns = $this->smallExpnRepository->create($input);

        return $this->sendResponse($smallExpns->toArray(), 'Small Expn saved successfully');
    }

    /**
     * Display the specified SmallExpn.
     * GET|HEAD /smallExpns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SmallExpn $smallExpn */
        $smallExpn = $this->smallExpnRepository->findWithoutFail($id);

        if (empty($smallExpn)) {
            return $this->sendError('Small Expn not found');
        }

        return $this->sendResponse($smallExpn->toArray(), 'Small Expn retrieved successfully');
    }

    /**
     * Update the specified SmallExpn in storage.
     * PUT/PATCH /smallExpns/{id}
     *
     * @param  int $id
     * @param UpdateSmallExpnAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSmallExpnAPIRequest $request)
    {
        $input = $request->all();

        /** @var SmallExpn $smallExpn */
        $smallExpn = $this->smallExpnRepository->findWithoutFail($id);

        if (empty($smallExpn)) {
            return $this->sendError('Small Expn not found');
        }

        $smallExpn = $this->smallExpnRepository->update($input, $id);

        return $this->sendResponse($smallExpn->toArray(), 'SmallExpn updated successfully');
    }

    /**
     * Remove the specified SmallExpn from storage.
     * DELETE /smallExpns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SmallExpn $smallExpn */
        $smallExpn = $this->smallExpnRepository->findWithoutFail($id);

        if (empty($smallExpn)) {
            return $this->sendError('Small Expn not found');
        }

        $smallExpn->delete();

        return $this->sendResponse($id, 'Small Expn deleted successfully');
    }
}
