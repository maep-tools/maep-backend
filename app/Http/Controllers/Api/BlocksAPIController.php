<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBlocksAPIRequest;
use App\Http\Requests\API\UpdateBlocksAPIRequest;
use App\Models\Blocks;
use App\Repositories\BlocksRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Routing\Controller as BaseController;
use Response;

/**
 * Class BlocksController
 * @package App\Http\Controllers\API
 */

class BlocksAPIController extends BaseController
{
    /** @var  BlocksRepository */
    private $blocksRepository;

    public function __construct(BlocksRepository $blocksRepo)
    {
        $this->blocksRepository = $blocksRepo;
    }

    /**
     * Display a listing of the Blocks.
     * GET|HEAD /blocks
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->blocksRepository->pushCriteria(new RequestCriteria($request));
        $this->blocksRepository->pushCriteria(new LimitOffsetCriteria($request));
        $blocks = $this->blocksRepository->all();

        return $this->sendResponse($blocks->toArray(), 'Blocks retrieved successfully');
    }


    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Store a newly created Blocks in storage.
     * POST /blocks
     *
     * @param CreateBlocksAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBlocksAPIRequest $request)
    {
        $input = $request->all();

        $blocks = $this->blocksRepository->create($input);

        return $this->sendResponse($blocks->toArray(), 'Blocks saved successfully');
    }

    /**
     * Display the specified Blocks.
     * GET|HEAD /blocks/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Blocks $blocks */
        $blocks = $this->blocksRepository->findWithoutFail($id);

        if (empty($blocks)) {
            return $this->sendResponse('Blocks not found');
        }

        return $this->sendResponse($blocks->toArray(), 'Blocks retrieved successfully');
    }

    /**
     * Update the specified Blocks in storage.
     * PUT/PATCH /blocks/{id}
     *
     * @param  int $id
     * @param UpdateBlocksAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBlocksAPIRequest $request)
    {
        $input = $request->all();

        /** @var Blocks $blocks */
        $blocks = $this->blocksRepository->findWithoutFail($id);

        if (empty($blocks)) {
            return $this->sendResponse('Blocks not found');
        }

        $blocks = $this->blocksRepository->update($input, $id);

        return $this->sendResponse($blocks->toArray(), 'Blocks updated successfully');
    }

    /**
     * Remove the specified Blocks from storage.
     * DELETE /blocks/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Blocks $blocks */
        $blocks = $this->blocksRepository->findWithoutFail($id);

        if (empty($blocks)) {
            return $this->sendResponse('Blocks not found');
        }

        $blocks->delete();

        return $this->sendResponse($id, 'Blocks deleted successfully');
    }
}
