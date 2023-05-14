<?php

namespace Modules\ProjectAttachment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\ProjectAttachment\Repositories\ProjectAttachmentRepository;
use Modules\ProjectAttachment\Http\Requests\CreateProjectAttachmentRequest;

/**
 * Class ProjectAttachmentController
 *
 * ProjectAttachment create, update, delete and view
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectAttachmentController extends Controller
{
    protected $projectattachmentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param ProjectAttachmentRepository $projectattachment [Object]
     *
     * @return void
     */
    public function __construct(ProjectAttachmentRepository $projectattachment)
    {
        $this->projectattachmentRepo = $projectattachment;
    }

    /**
     * Get project attachment by project.
     *
     * @param Int $project_id [Project id]
     *
     * @return Response
     */
    public function show($project_id)
    {
        $projectattachment = $this->projectattachmentRepo->findByProjectId(
            $project_id
        );
        if ($projectattachment) {
            return response()->json($projectattachment);
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create project attachment]
     *
     * @return Response
     */
    public function store(CreateProjectAttachmentRequest $request)
    {
        $projectattachment = $this->projectattachmentRepo->create($request);

        if ($projectattachment) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy project attachment]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check project user permission
        if (!$this->projectattachmentRepo->checkPermission($id, 'delete')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->projectattachmentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
