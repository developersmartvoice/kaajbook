<?php

namespace Modules\Incident\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Incident\Repositories\IncidentRepository;
use Modules\Incident\Http\Requests\IncidentRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class IncidentController
 *
 * Incident create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Incident
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentController extends Controller
{
    protected $incidentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param IncidentRepository $incident [Object]
     *
     * @return void
     */
    public function __construct(IncidentRepository $incident)
    {
        $this->incidentRepo = $incident;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->incidentRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get incidents]
     *
     * @return Response
     */
    public function getAllIncidents(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->incidentRepo->getAllIncidents($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get incident by project]
     *
     * @return Response
     */
    public function getIncidentForKanban(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->incidentRepo->getIncidentForKanban($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IncidentRequest $request [Request for create incidents]
     *
     * @return Response
     */
    public function store(IncidentRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'created')) {
            return response()->json("Access denied", 403);
        }

        $incident = $this->incidentRepo->create($request);

        if ($incident) {
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
        if (!AdminHelper::can_action(47, 'view')) {
            return response()->json("Access denied", 403);
        }

        $incident = $this->incidentRepo->findByIdWithHistory($id);
        if ($incident) {
            return response()->json($incident);
        }
        return response()->json('error', 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update incidents]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->incidentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        $incident = $this->incidentRepo->update($request, $id);

        if ($incident) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy incidents]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->incidentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->incidentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update incidents notes]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function incidentNotesUpdate(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->incidentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        $incident = $this->incidentRepo->incidentNotesUpdate($request, $id);

        if ($incident) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * Change incident status.
     *
     * @param Request $request [Request for change status]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->incidentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        $incident = $this->incidentRepo->changeStatus($request, $id);

        if ($incident) {
            return response()->json('success');
        } else {
            return response()->json('error', 403);
        }
    }

    /**
     * Change incident severity.
     *
     * @param Request $request [Request for change incident severity]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeIncidentSeverity(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->incidentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->incidentRepo->changeIncidentSeverity($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Get incident for report.
     *
     * @param Request $request [Request for get incident report]
     *
     * @return Response
     */
    public function getIncidentForReport(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(51, 'view')) {
            return response()->json("Access denied", 403);
        }
        
        return $this->incidentRepo->getIncidentForReport($request);
    }

    /**
     * Get incidents for calendar.
     *
     * @param Request $request [Request for get incidents]
     *
     * @return Response
     */
    public function getIncidentForCalendar(Request $request)
    {   
        return $this->incidentRepo->getIncidentForCalendar($request);
    }

    /**
     * Get incident permission.
     *
     * @param Request $request [Request for get permission]
     *
     * @return Response
     */
    public function getIncidentPermission(Request $request, $id)
    {
        return $this->incidentRepo->checkPermission($id, $request->get('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update incident status and order]
     *
     * @return Response
     */
    public function updateKanban(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(47, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $incident = $this->incidentRepo->updateKanban($request);

        if ($incident) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
}
