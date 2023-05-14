<?php

namespace Modules\KnowledgeBaseCategory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\KnowledgeBaseCategory\Repositories\KnowledgeBaseCategoryRepository;
use Modules\KnowledgeBaseCategory\Http\Requests\KnowledgeBaseCategoryRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class KnowledgeBaseCategoryController
 *
 * Knowledge Base Category create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\KnowledgeBaseCategory
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class KnowledgeBaseCategoryController extends Controller
{
    protected $knowledgeBaseCategoryRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param KnowledgeBaseCategoryRepository $knowledgeBaseCategory [Object]
     *
     * @return void
     */
    public function __construct(
        KnowledgeBaseCategoryRepository $knowledgeBaseCategory
    ) {
        $this->knowledgeBaseCategoryRepo = $knowledgeBaseCategory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->knowledgeBaseCategoryRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KnowledgeBaseCategoryRequest $request [Request for create KB category]
     *
     * @return Response
     */
    public function store(KnowledgeBaseCategoryRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(49, 'created')) {
            return response()->json("Access denied", 403);
        }

        $knowledgeBaseCategory = $this->knowledgeBaseCategoryRepo->create($request);

        if ($knowledgeBaseCategory) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param Int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(49, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->knowledgeBaseCategoryRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param KnowledgeBaseCategoryRequest $request [Request for update KB category]
     * @param Int                          $id      [Row id]
     *
     * @return Response
     */
    public function update(KnowledgeBaseCategoryRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(49, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $knowledgeBaseCategory = $this->knowledgeBaseCategoryRepo->update(
            $request,
            $id
        );

        if ($knowledgeBaseCategory) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy KB category]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(49, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->knowledgeBaseCategoryRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Search the specified resource.
     *
     * @param String $search [Search artical string]
     *
     * @return Response
     */
    public function searchCategoryArticle(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(49, 'view')) {
            return response()->json("Access denied", 403);
        }
        
        return $this->knowledgeBaseCategoryRepo->searchCategoryArticle($request->query('search'));
    }
}
