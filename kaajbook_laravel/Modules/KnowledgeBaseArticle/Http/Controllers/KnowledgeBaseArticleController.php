<?php

namespace Modules\KnowledgeBaseArticle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\KnowledgeBaseArticle\Repositories\KnowledgeBaseArticleRepository;
use Modules\KnowledgeBaseArticle\Http\Requests\KnowledgeBaseArticleRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class KnowledgeBaseArticleController
 *
 * Knowledge Base Article create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\KnowledgeBaseArticle
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class KnowledgeBaseArticleController extends Controller
{
    protected $knowledgeBaseArticleRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param KnowledgeBaseArticleRepository $knowledgeBaseArticle [Object]
     *
     * @return void
     */
    public function __construct(KnowledgeBaseArticleRepository $knowledgeBaseArticle)
    {
        $this->knowledgeBaseArticleRepo = $knowledgeBaseArticle;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->knowledgeBaseArticleRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KnowledgeBaseArticleRequest $request [Request for create knowledgebase]
     *
     * @return Response
     */
    public function store(KnowledgeBaseArticleRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(49, 'created')) {
            return response()->json("Access denied", 403);
        }

        $knowledgeBaseArticle = $this->knowledgeBaseArticleRepo->create($request);

        if ($knowledgeBaseArticle) {
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

        return $this->knowledgeBaseArticleRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update knowledgebase artical]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function update(KnowledgeBaseArticleRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(49, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $knowledgeBaseArticle = $this->knowledgeBaseArticleRepo->update(
            $request,
            $id
        );

        if ($knowledgeBaseArticle) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy knowledgebase artical]
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

        if ($this->knowledgeBaseArticleRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
