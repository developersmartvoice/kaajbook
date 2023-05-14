<?php

namespace Modules\TaskComment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\TaskComment\Http\Requests\CreateTaskCommentRequest;
use Modules\TaskComment\Http\Requests\UpdateTaskCommentRequest;
use Modules\TaskComment\Repositories\TaskCommentRepository;

/**
 * Class TaskCommentController
 *
 * TaskComment create, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\TaskComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TaskCommentController extends Controller
{
    protected $taskCommentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param TaskCommentRepository $taskComment [Object]
     *
     * @return void
     */
    public function __construct(TaskCommentRepository $taskComment)
    {
        $this->taskCommentRepo = $taskComment;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTaskCommentRequest $request [Request for create task comment]
     *
     * @return Response
     */
    public function store(CreateTaskCommentRequest $request)
    {
        $taskComment = $this->taskCommentRepo->create($request);

        if ($taskComment) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskCommentRequest $request [Request for update task comment]
     * @param int               $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateTaskCommentRequest $request, $id)
    {
        if ($this->taskCommentRepo->update($request, $id)) {
            return response()->json("success");
        } else {
            return response()->json("error", 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy task comment]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check task user permission
        if (!$this->taskCommentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->taskCommentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
