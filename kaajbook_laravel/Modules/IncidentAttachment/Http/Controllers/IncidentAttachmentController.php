<?php

namespace Modules\IncidentAttachment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\IncidentAttachment\Repositories\IncidentAttachmentRepository;
use Modules\IncidentAttachment\Http\Requests\IncidentAttachmentRequest;

/**
 * Class IncidentAttachmentController
 *
 * Incident attachment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\IncidentAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentAttachmentController extends Controller
{
    protected $incidentattachmentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param IncidentAttachmentRepository $incidentattachment [Object]
     *
     * @return void
     */
    public function __construct(IncidentAttachmentRepository $incidentattachment)
    {
        $this->incidentattachmentRepo = $incidentattachment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->incidentattachmentRepo->findAll();
    }

    /**
     * Get incident attachment by incident.
     *
     * @param Int $incident_id [Incident id]
     *
     * @return Response
     */
    public function show($incident_id)
    {
        $incidentattachment = $this->incidentattachmentRepo
            ->findByIncidentId($incident_id);
        if ($incidentattachment) {
            return response()->json($incidentattachment);
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create incident attachment]
     *
     * @return Response
     */
    public function store(IncidentAttachmentRequest $request)
    {
        $incidentattachment = $this->incidentattachmentRepo->create($request);

        if ($incidentattachment) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy incident attachment]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check task user permission
        if (!$this->incidentattachmentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->incidentattachmentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
