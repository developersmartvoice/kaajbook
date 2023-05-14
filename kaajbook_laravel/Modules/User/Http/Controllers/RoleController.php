<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Http\Requests\CreateRoleRequest;
use Modules\User\Http\Requests\UpdateRoleRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class RoleController
 *
 * Role create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Role
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class RoleController extends Controller
{
    protected $roleRepo;
    
    /**
     * Instantiate a new repository instance.
     *
     * @param RoleRepository $role [Object]
     *
     * @return void
     */
    public function __construct(RoleRepository $role)
    {
        $this->roleRepo  = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->roleRepo->getRoles();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get role]
     *
     * @return Response
     */
    public function getAllRoles(Request $request)
    {
        return $this->roleRepo->getAllRoles($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRoleRequest $request [Request for create role]
     *
     * @return Response
     */
    public function store(CreateRoleRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(4, 'created')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Create role
        if ($this->roleRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Role has not been created.'], 400);
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
        if (!AdminHelper::can_action(4, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->roleRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update role]
     * @param Int     $id      [Role id]
     *
     * @return Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(4, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Update role
        if ($this->roleRepo->update($id, $request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Role has not been found.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy role]
     * @param Int     $id      [Role id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(4, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Delete role
        if ($this->roleRepo->delete($id, $request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Role has not been found.'], 400);
        }
    }
}
