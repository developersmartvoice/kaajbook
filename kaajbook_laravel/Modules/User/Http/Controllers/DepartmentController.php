<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\User\Repositories\DepartmentRepository;
use Modules\User\Http\Requests\CreateDepartmentRequest;
use Modules\User\Http\Requests\UpdateDepartmentRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class DepartmentController
 *
 * Department create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DepartmentController extends Controller
{
    protected $dpRepo;

    /**
     * Create a new DepartmentController instance.
     *
     * @param DepartmentRepository $dpRepo [Object]
     *
     * @return void
     */
    public function __construct(DepartmentRepository $dpRepo)
    {
        $this->dpRepo = $dpRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->dpRepo->getAllDepartments();
    }

    /**
     * Get department wise roles for options.
     *
     * @return Response
     */
    public function getDepartmentRoles()
    {
        return $this->dpRepo->getDepartmentRoles();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateDepartmentRequest $request [Request for create department]
     *
     * @return Response
     */
    public function store(CreateDepartmentRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(5, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->dpRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Department has not been created.'], 
                400
            );
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
        if (!AdminHelper::can_action(5, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->dpRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDepartmentRequest $request [Request for update department]
     * @param Int                     $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateDepartmentRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(5, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->dpRepo->update($id, $request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Department has not been found.'], 
                400
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy department]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(5, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->dpRepo->delete($id, $request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Department has not been found.'], 
                400
            );
        }
    }

    /**
     * Delete departments role.
     *
     * @param Int     $departmentId [Department id]
     * @param Int     $roleId       [Role id]
     * @param Request $request      [Request for delete department]
     *
     * @return Response
     */
    public function deleteDepartmentRole($departmentId, $roleId, Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(4, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->dpRepo->deleteDepartmentRole($departmentId, $roleId, $request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Department roles has not been found.'], 
                400
            );
        }
    }

    /**
     * Get all departments/roles/menus.
     *
     * @param Int $departmentId [Department id]
     * @param Int $roleId       [Role id]
     *
     * @return Response
     */
    public function getDepartmentDetail($departmentId, $roleId)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(5, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->dpRepo->getDepartmentDetail($departmentId, $roleId);
    }

    /**
     * Update all departments/roles/menus.
     *
     * @param Int     $departmentId [Department id]
     * @param Int     $roleId       [Role id]
     * @param Request $request      [Request for update department detail]
     *
     * @return Response
     */
    public function putDepartmentDetail($departmentId, $roleId, Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(5, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->dpRepo->putDepartmentDetail($departmentId, $roleId, $request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Department has not been found.'], 
                400
            );
        }
    }

    /**
     * Get all departments/clients/roles.
     *
     * @param Request $request [Request for get department clients role]
     *
     * @return Response
     */
    public function getDepartmentsClientsRoles(Request $request)
    {
        return $this->dpRepo->getDepartmentsClientsRoles($request);
    }
}
