<?php
namespace Modules\User\Repositories;

use Modules\User\Models\Role\Role;

/**
 * Class RoleRepository
 *
 * Role create, update, delete and view.
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
class RoleRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return Array
     */
    public function getRoles()
    {
        return Role::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get role]
     *
     * @return Array
     */
    public function getAllRoles($request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'slug',
            3 => 'description'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $roles = new Role();
        $totalData = $roles->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $roles = $roles->where(
                function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('slug', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $roles->count();
        }

        $data = $roles->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        return array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create role]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $role = new Role;
        $role->fill($input);

        if ($role->save()) {
            // --
            // Add activities
            createUserActivity(
                Role::MODULE_NAME,
                $role->id,
                $request->method(),
                $role->name,
                $request->ip()
            );
            return $role;
        }
        return false;
    }

    /**
     * Display the specified resource in storage.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return Role::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Int     $id      [Row id]
     * @param Request $request [Request for update role]
     *
     * @return Boolean
     */
    public function update($id, $request)
    {
        $role = Role::find($id);
        if ($role->fill($request->all())->save()) {
            // --
            // Add activities
            createUserActivity(
                Role::MODULE_NAME,
                $role->id,
                $request->method(),
                $role->name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Int     $id      [Row id]
     * @param Request $request [Request for delete role]
     *
     * @return Boolean
     */
    public function delete($id, $request)
    {
        $role = Role::find($id);

        if (!empty($role)) {
            $role->status = '0';
            $role->save();
            $role->delete();

            // --
            // Add activity
            createUserActivity(
                Role::MODULE_NAME,
                $id,
                $request->method(),
                $role->name,
                $request->ip()
            );

            return true;
        } else {
            return false;
        }
    }
}
