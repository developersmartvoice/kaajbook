<?php

namespace Modules\Defect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Defect\Repositories\DefectRepository;
use Modules\Defect\Http\Requests\CreateDefectRequest;
use Modules\Defect\Http\Requests\UpdateDefectRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class DefectController
 *
 * Defect create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Defect
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DefectController extends Controller
{
    protected $defectRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param DefectRepository $defect [Object]
     *
     * @return void
     */
    public function __construct(DefectRepository $defect)
    {
        $this->defectRepo = $defect;
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
        if (!AdminHelper::can_action(46, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->defectRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get defects]
     *
     * @return Response
     */
    public function getAllDefects(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->defectRepo->getAllDefects($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get defect by project]
     *
     * @return Response
     */
    public function getDefectForKanban(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->defectRepo->getDefectForKanban($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create defect]
     *
     * @return Response
     */
    public function store(CreateDefectRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'created')) {
            return response()->json("Access denied", 403);
        }

        $defect = $this->defectRepo->create($request);

        if ($defect) {
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
        if (!AdminHelper::can_action(46, 'view')) {
            return response()->json("Access denied", 403);
        }

        $defects = $this->defectRepo->findByIdWithHistory($id);
        if ($defects) {
            return response()->json($defects);
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update defect]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateDefectRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->defectRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        $defect = $this->defectRepo->update($request, $id);

        if ($defect) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update defect notes]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function defectNotesUpdate(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->defectRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        $defect = $this->defectRepo->defectNotesUpdate($request, $id);

        if ($defect) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy defect]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->defectRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->defectRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 403);
        }
    }

    /**
     * Change defect status.
     *
     * @param Request $request [Request for change defect status]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeDefectStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->defectRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->defectRepo->changeDefectStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Change defect severity.
     *
     * @param Request $request [Request for change defect severity]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeDefectSeverity(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        // --
        // Check task user permission
        if (!$this->defectRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->defectRepo->changeDefectSeverity($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for defect report]
     *
     * @return Response
     */
    public function getDefectForReport(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(51, 'view')) {
            return response()->json("Access denied", 403);
        }
        
        return $this->defectRepo->getDefectForReport($request);
    }

    /**
     * Get defects for calendar.
     *
     * @param Request $request [Request for get defects]
     *
     * @return Response
     */
    public function getDefectForCalendar(Request $request)
    {   
        return $this->defectRepo->getDefectForCalendar($request);
    }

    /**
     * Get defect permission.
     *
     * @param Request $request [Request for get permission]
     *
     * @return Response
     */
    public function getDefectPermission(Request $request, $id)
    {
        return $this->defectRepo->checkPermission($id, $request->get('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update defect status and order]
     *
     * @return Response
     */
    public function updateKanban(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(46, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $defect = $this->defectRepo->updateKanban($request);

        if ($defect) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
}
