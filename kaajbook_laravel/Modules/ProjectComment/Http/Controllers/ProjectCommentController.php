<?php

namespace Modules\ProjectComment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ProjectComment\Http\Requests\ProjectCommentRequest;
use Modules\ProjectComment\Repositories\ProjectCommentRepository;

/**
 * Class ProjectCommentController
 *
 * Project comment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectCommentController extends Controller
{
    protected $projectcommentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param ProjectCommentRepository $projectcomment [Object]
     *
     * @return void
     */
    public function __construct(ProjectCommentRepository $projectcomment)
    {
        $this->projectcommentRepo = $projectcomment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->projectcommentRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectCommentRequest $request [create project comment]
     *
     * @return Response
     */
    public function store(ProjectCommentRequest $request)
    {
        $projectcomment = $this->projectcommentRepo->create($request);

        if ($projectcomment) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectCommentRequest $request [Request for update project comment]
     * @param int               $id      [Row id]
     *
     * @return Response
     */
    public function update(ProjectCommentRequest $request, $id)
    {
        if ($this->projectcommentRepo->update($request, $id)) {
            return response()->json("success");
        } else {
            return response()->json("error", 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy project comment]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!$this->projectcommentRepo->checkPermission($id, 'delete')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->projectcommentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
