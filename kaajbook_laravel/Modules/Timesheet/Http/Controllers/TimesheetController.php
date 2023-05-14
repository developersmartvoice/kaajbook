<?php

namespace Modules\Timesheet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Helper\Helpers\AdminHelper;
use Modules\Timesheet\Http\Requests\TimesheetRequest;
use Modules\Timesheet\Repositories\TimesheetRepository;

/**
 * Class TimesheetController
 *
 * Timesheet create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Timesheet
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TimesheetController extends Controller
{
    protected $timesheetRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param TimesheetRepository $timesheet [Object]
     *
     * @return void
     */
    public function __construct(TimesheetRepository $timesheet)
    {
        $this->timesheetRepo = $timesheet;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->timesheetRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get timesheet]
     *
     * @return Response
     */
    public function getTimesheetsByModule(Request $request)
    {
        return $this->timesheetRepo->getTimesheetsByModule($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get my timesheet]
     *
     * @return Response
     */
    public function getMyTimesheets(Request $request)
    {
        return $this->timesheetRepo->getMyTimesheets($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get timesheet report]
     *
     * @return Response
     */
    public function getTimesheetsReport(Request $request)
    {
        return $this->timesheetRepo->getTimesheetsReport($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get project timesheet]
     *
     * @return Response
     */
    public function getProjectTimesheets(Request $request)
    {
        return $this->timesheetRepo->getProjectTimesheets($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TimesheetRequest $request [Request for create team]
     *
     * @return Response
     */
    public function store(TimesheetRequest $request)
    {
        // --
        // Check role/permission
        // if (!AdminHelper::can_action(29, 'created')) {
        //     return response()->json("Access denied", 403);
        // }

        if ($this->timesheetRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Timesheet has not been created.'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update timesheet]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function update(TimesheetRequest $request, $id)
    {
        // --
        // Check task user permission
        if (!$this->timesheetRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        $timesheet = $this->timesheetRepo->update($request, $id);

        if ($timesheet) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy timesheet]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check task user permission
        if (!$this->timesheetRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->timesheetRepo->delete($request, $id)) {
            return response()->json('success', 200);
        } else {
            return response()->json(['error' => 'Error while deleting.'], 400);
        }
    }
}
