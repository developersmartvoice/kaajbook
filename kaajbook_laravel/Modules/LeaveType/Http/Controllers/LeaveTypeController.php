<?php

namespace Modules\LeaveType\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\LeaveType\Http\Requests\LeaveTypeRequest;
use Modules\LeaveType\Repositories\LeaveTypeRepository;

/**
 * Class LeaveTypeController
 *
 * Leave type CRUD functionality.
 *
 * Leave type create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\LeaveType
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class LeaveTypeController extends Controller
{
    protected $leaveTypeRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param LeaveTypeRepository $leaveType [Object]
     *
     * @return void
     */
    public function __construct(LeaveTypeRepository $leaveType)
    {
        $this->leaveTypeRepo = $leaveType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->leaveTypeRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeaveTypeRequest $request [Params for create leave type]
     *
     * @return Response
     */
    public function store(LeaveTypeRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(31, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->leaveTypeRepo->create($request)) {
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
        if (!AdminHelper::can_action(31, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->leaveTypeRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LeaveTypeRequest $request [Params for update leave type]
     * @param int              $id      [Row id]
     *
     * @return Response
     */
    public function update(LeaveTypeRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(31, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->leaveTypeRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for destroy leave type]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(31, 'deleted')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->leaveTypeRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
}
