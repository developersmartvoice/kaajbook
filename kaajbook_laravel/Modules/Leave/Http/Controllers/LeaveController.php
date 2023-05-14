<?php

namespace Modules\Leave\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Leave\Http\Requests\CreateLeaveRequest;
use Modules\Leave\Http\Requests\UpdateLeaveRequest;
use Modules\Leave\Models\Leave;
use Modules\Leave\Repositories\LeaveRepository;

/**
 * Class LeaveController
 *
 * Leave create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\Leave
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class LeaveController extends Controller
{
    protected $leaveRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param LeaveRepository $leaveRepo [Object]
     *
     * @return void
     */
    public function __construct(LeaveRepository $leaveRepo)
    {
        $this->leaveRepo = $leaveRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get leaves]
     *
     * @return Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get pending leaves for box layout]
     *
     * @return Response
     */
    public function getLeavesForBox(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(102, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->leaveRepo->getLeavesForBox($request);
    }

    /**
     * Display a listing of the resource for calendar.
     *
     * @param Request $request [Request for get leaves for calendar]
     *
     * @return Array
     */
    public function getLeavesForCalendar(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(102, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->leaveRepo->getLeavesForCalendar($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get my leaves]
     *
     * @return Response
     */
    public function getAllLeave(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(102, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->leaveRepo->getAllLeave($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateLeaveRequest $request [Params for create leave]
     *
     * @return Response
     */
    public function store(CreateLeaveRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(102, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($request->get('duration') == 'multiple') {
            $isExist = Leave::where("user_id", $request->get('user_id'))
                ->whereIn("leave_date", $request->get('multi_date'))
                ->where('deleted_at', null)
                ->exists();

            if ($isExist) {
                return response()->json(['errors' => ['leave_date' => ['The leave date has already been taken.']]], 422);
            }
        }

        if ($this->leaveRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(102, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->leaveRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLeaveRequest $request [Params for update leave]
     * @param int                $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateLeaveRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(102, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->leaveRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for destroy leave]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(102, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->leaveRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * Change leave status.
     *
     * @param Request $request [Request for change leave status]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeLeaveStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(102, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->leaveRepo->changeLeaveStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Display a listing of the resource for report.
     *
     * @param Request $request [Request for get leaves report data]
     *
     * @return Array
     */
    public function getLeavesForReport(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(51, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->leaveRepo->getLeavesForReport($request);
    }

    /**
     * Display a listing of the resource for report detail.
     *
     * @param Request $request [Request for get user leaves details]
     *
     * @return Array
     */
    public function leaveReportDetails(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(51, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->leaveRepo->leaveReportDetails($request);
    }
}
