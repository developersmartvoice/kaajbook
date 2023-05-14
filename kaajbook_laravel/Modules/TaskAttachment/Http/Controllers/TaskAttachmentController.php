<?php

namespace Modules\TaskAttachment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\TaskAttachment\Repositories\TaskAttachmentRepository;
use Modules\TaskAttachment\Http\Requests\CreateTaskAttachmentRequest;

/**
 * Class TaskAttachmentController
 *
 * Task attachment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\TaskAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TaskAttachmentController extends Controller
{
    protected $taskAttachmentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param TaskAttachmentRepository $taskAttachment [Object]
     *
     * @return void
     */
    public function __construct(TaskAttachmentRepository $taskAttachment)
    {
        $this->taskAttachmentRepo = $taskAttachment;
    }

    /**
     * Get task attachment by task.
     *
     * @param Int $task_id [Task id]
     *
     * @return Response
     */
    public function show($task_id)
    {
        $taskAttachment = $this->taskAttachmentRepo->findByTaskId($task_id);
        if ($taskAttachment) {
            return response()->json($taskAttachment);
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTaskAttachmentRequest $request [create task attachment]
     *
     * @return Response
     */
    public function store(CreateTaskAttachmentRequest $request)
    {
        $taskAttachment = $this->taskAttachmentRepo->create($request);

        if ($taskAttachment) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy task attachment]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check task user permission
        if (!$this->taskAttachmentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->taskAttachmentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
