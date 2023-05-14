<?php
namespace Modules\User\Repositories;

use Modules\User\Models\Role\Role;
use Modules\Menu\Models\Menu;
use Modules\User\Models\Department\Department;
use Modules\User\Models\Department\DepartmentRoleMenu;

/**
 * Class DepartmentRepository
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
class DepartmentRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return Array
     */
    public function getAllDepartments()
    {
        $departments = Department::with(
            ['roles' => function ($q) {
                // $q->groupBy('id');
            }]
        )->get();

        if (!empty($departments)) {
            foreach ($departments as $department) {
                foreach ($department->roles as $role) {
                    $role->users = Role::where('id', $role->id)->with(
                        ['users' => function ($query) use ($department) {
                            $query->wherePivot('department_id', $department->id);
                        }]
                    )->first()->users->count();
                }
            }
        }
        return $departments;
    }

    /**
     * Get department wise roles for options.
     *
     * @return Array
     */
    public function getDepartmentRoles()
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return Department::with(['roles'])->get();
        }else{
            return Department::with(['roles'])->whereIn('id', $user->departments->pluck('id'))->get();
        }
    }

    /**
     * Get user by ID.
     *
     * @param Int $id [Row id]
     *
     * @return Response
     */
    public function findById($id)
    {
        return Department::with(
            ['roles' => function ($query) {
                $query->select(config('core.acl.roles_table').'.id')
                    ->get()
                    ->pluck('id');
            }]
        )->where(config('core.acl.departments_table').'.id', $id)
        ->first()
        ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create department]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $department = new Department;
        $department->fill($input)->save();
        if ($department->save()) {
            $department->roles()->sync($request->get('department_roles'));

            // --
            // Assign permissions
            $menus = Menu::get();
            if (!empty($menus)) {
                foreach ($menus as $key => $value) {
                    foreach ($request->get('department_roles') as $rKey => $rValue) {
                        $mdata = [
                         'view'=> $value->id,
                         'created'=> 0,
                         'edited'=> 0,
                         'deleted'=> 0
                        ];
                        $departmentMenu = DepartmentRoleMenu::firstOrNew(
                            array(
                                'department_id' => $department->id,
                                'role_id' => $rValue,
                                'menu_id' => $value->id
                            )
                        );
                        $departmentMenu->fill($mdata);
                        $departmentMenu->save();
                    }
                }
            }
            // --
            // Add activities
            createUserActivity(
                Department::MODULE_NAME,
                $department->id,
                $request->method(),
                $department->name,
                $request->ip()
            );
            return $department;
        }
        return false;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param Int     $id      [Row id]
     * @param Request $request [Request for update department]
     *
     * @return Boolean
     */
    public function update($id, $request)
    {
        $input = $request->all();
        $department = Department::find($id);
        $department->fill($input);

        if ($department->save()) {
            $department->roles()->sync($request->get('department_roles'));
            // --
            // Add activities
            createUserActivity(
                Department::MODULE_NAME,
                $department->id,
                $request->method(),
                $department->name,
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
     * @param Request $request [Request for delete department]
     *
     * @return Boolean
     */
    public function delete($id, $request)
    {
        $department = Department::find($id);

        if (!empty($department)) {
            $department->roles()->sync([]);
            $department->delete();
            // --
            // Add activities
            createUserActivity(
                Department::MODULE_NAME,
                $department->id,
                $request->method(),
                $department->name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Delete departments role.
     *
     * @param Int     $departmentId [Department id]
     * @param Int     $roleId       [Role id]
     * @param Request $request      [Request for delete department role]
     *
     * @return Response
     */
    public function deleteDepartmentRole($departmentId, $roleId, $request)
    {
        $department = Department::find($departmentId);
        if (!empty($department)) {
            $department->roles()->detach($roleId);
            return true;
        }
        return false;
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
        $departments = [];
        $menu = [];
        $result = [];

        // -
        // Get selected menus
        $department_role_menu_table = config('core.acl.department_role_menu_table');
        $menu_table = config('core.acl.menu_table');

        $selectedMenus = DepartmentRoleMenu::join($menu_table, $department_role_menu_table . '.menu_id', '=', $menu_table.'.id')
            ->where($department_role_menu_table. '.department_id', $departmentId)
            ->where($department_role_menu_table. '.role_id', $roleId)
            ->get();

        if (!empty($selectedMenus)) {
            foreach ($selectedMenus as $value) {
                $result[$value->id] = [
                 'id' => $value->id,
                 'view' => $value->view,
                 'created' => $value->created,
                 'edited' => $value->edited,
                 'deleted' => $value->deleted,
                ];
            }
            $departments['permissions'] = $result;
        }

        // --
        // Department/roles
        $departments['department'] = Department::with(
            ['roles' => function ($query) use ($roleId) {
                $query->select(
                    config('core.acl.roles_table').'.id',
                    config('core.acl.roles_table').'.name'
                )
                    ->where(config('core.acl.roles_table').'.id', $roleId);
            }]
        )->select('id', 'name')->find($departmentId);

        // --
        // Build Menus
        $menu_info = Menu::where('status', 1)->orderBy('order')->get();
        foreach ($menu_info as $items) {
            $menu['parents'][$items->parent_menu_id][] = $items;
        }

        $departments['menus'] = $this->buildChild(0, $menu);
        $departments['menus_items'] = $menu_info;

        return $departments;
    }

    /**
     * Build child menu.
     *
     * @param Array $parent [Parent array]
     * @param Array $menu   [Menu array]
     *
     * @return Array
     */
    public function buildChild($parent, $menu)
    {
        $result = [];
        if (isset($menu['parents'][$parent])) {
            foreach ($menu['parents'][$parent] as $ItemID) {
                if (!isset($menu['parents'][$ItemID->id])) {
                    $result[$ItemID->text] = $ItemID->id;
                }
                if (isset($menu['parents'][$ItemID->id])) {
                    $result[$ItemID->text][$ItemID->id] = self::buildChild(
                        $ItemID->id,
                        $menu
                    );
                }
            }
        }
        return $result;
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
    public function putDepartmentDetail($departmentId, $roleId, $request)
    {
        $input = $request->all();

        // --
        // Remove old department/roles/menus
        $departments = DepartmentRoleMenu::where('department_id', $departmentId)
            ->where('role_id', $roleId)
            ->get();

        if (!empty($departments)) {
            DepartmentRoleMenu::where('department_id', $departmentId)
                ->where('role_id', $roleId)
                ->delete();
        }

        // --
        // Assign new departments/roles/menus
        if (!empty($input['menu'])) {
            foreach ($input['menu'] as $key => $value) {
                $mdata = [
                    'view'=> 1,
                    'created'=> 0,
                    'edited'=> 0,
                    'deleted'=> 0
                ];

                if(!empty($input['submenu'])) {
                    if (isset($input['submenu'][$value]) && $input['submenu'][$value]) {
                        $mdata = $input['submenu'][$value];
                    }
                }

                // --
                // Save menus
                $departmentMenu = DepartmentRoleMenu::firstOrNew(
                    array(
                    'department_id' => $departmentId,
                    'role_id' => $roleId,
                    'menu_id' => $value
                    )
                );

                $departmentMenu->fill($mdata);
                $departmentMenu->save();
            }

            // --
            // Add activities
            $department = Department::find($departmentId);
            createUserActivity(
                Department::MODULE_NAME,
                $department->id,
                $request->method(),
                $department->name,
                $request->ip()
            );
            return true;
        }
        return true;
    }

    /**
     * Get all departments/clients/roles.
     *
     * @param Request $request [Request for get department clients role]
     *
     * @return Response
     */
    // public function getDepartmentsClientsRoles($request)
    // {
    //     $roles_table = config('core.acl.roles_table');
    //     $departments = Department::with(
    //         ['roles' => function ($q) use ($roles_table) {
    //             $q->whereIn($roles_table.'.id', [3]);
    //         }]
    //     )->get();

    //     if (!empty($departments)) {
    //         foreach ($departments as $department) {
    //             foreach ($department->roles as $role) {
    //                 $role->users = Role::where('id', $role->id)->with(
    //                     ['users' => function ($query) use ($department) {
    //                         $query->wherePivot('department_id', $department->id);
    //                     }]
    //                 )->first()->users->count();
    //             }
    //         }
    //     }
    //     return $departments;
    // }

    /**
     * Get departments wise client roles for options.
     *
     * @return Array
     */
    public function getDepartmentsClientsRoles()
    {
        $roles_table = config('core.acl.roles_table');
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return Department::with([
                'roles' => function ($q) use ($roles_table) {
                    $q->whereIn($roles_table.'.id', [3]);
                }
            ])->get();
        }else{
            return Department::with([
                'roles' => function ($q) use ($roles_table) {
                    $q->whereIn($roles_table.'.id', [3]);
                }
            ])->whereIn('id', $user->departments->pluck('id'))->get();
        }
    }
}
