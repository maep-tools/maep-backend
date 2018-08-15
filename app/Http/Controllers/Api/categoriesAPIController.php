<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatecategoriesAPIRequest;
use App\Http\Requests\API\UpdatecategoriesAPIRequest;
use App\Models\categories;
use App\Repositories\categoriesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Routing\Controller as BaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class categoriesController
 * @package App\Http\Controllers\API
 */

class categoriesAPIController extends BaseController
{
    /** @var  categoriesRepository */
    private $categoriesRepository;

    public function __construct(categoriesRepository $categoriesRepo)
    {
        $this->categoriesRepository = $categoriesRepo;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the categories.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->categoriesRepository->pushCriteria(new RequestCriteria($request));
        $this->categoriesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $categories = $this->categoriesRepository->all();

        $categories = $this->buildTree($categories->toArray());

        return $this->sendResponse($categories, 'Categories retrieved successfully');
    }

    function buildTree(array $elements, $parentId = -1) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parentId'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Store a newly created categories in storage.
     * POST /categories
     *
     * @param CreatecategoriesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatecategoriesAPIRequest $request)
    {
        $input = $request->all();

        $categories = $this->categoriesRepository->create($input);

        return $this->sendResponse($categories->toArray(), 'Categories saved successfully');
    }

    /**
     * Display the specified categories.
     * GET|HEAD /categories/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var categories $categories */
        $categories = $this->categoriesRepository->findWithoutFail($id);

        if (empty($categories)) {
            return $this->sendError('Categories not found');
        }

        return $this->sendResponse($categories->toArray(), 'Categories retrieved successfully');
    }

    /**
     * Update the specified categories in storage.
     * PUT/PATCH /categories/{id}
     *
     * @param  int $id
     * @param UpdatecategoriesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatecategoriesAPIRequest $request)
    {
        $input = $request->all();

        /** @var categories $categories */
        $categories = $this->categoriesRepository->findWithoutFail($id);

        if (empty($categories)) {
            return $this->sendError('Categories not found');
        }

        $categories = $this->categoriesRepository->update($input, $id);

        return $this->sendResponse($categories->toArray(), 'categories updated successfully');
    }

    /**
     * Remove the specified categories from storage.
     * DELETE /categories/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var categories $categories */
        $categories = $this->categoriesRepository->findWithoutFail($id);

        if (empty($categories)) {
            return $this->sendError('Categories not found');
        }

        $categories->delete();

        return $this->sendResponse($id, 'Categories deleted successfully');
    }
}
