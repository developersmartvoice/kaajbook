<?php
namespace Modules\Helper\Helpers;

use Modules\User\Models\Department\DepartmentRoleMenu;
use Modules\User\Models\User\User;

/**
 * Class AdminHelper
 *
 * The Helper is Defined for global helper functions.
 *
 * PHP version 7.1.3
 *
 * @category  Helper
 * @package   Modules\Helper
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AdminHelper
{
    /**
     * Check user role/permision for access resources.
     *
     * @param Int    $menu_id [Menu id]
     * @param String $action  [Action]
     * @param Int    $id      [Row id]
     *
     * @return Boolean
     */
    public static function can_action($menu_id, $action, $id = null)
    {
        if (empty(\Auth::user())) {
            return false;
        }

        $user = \Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        if($user->id == $id) {
            return true;
        }

        // --
        // Get departments/roles
        $departments = $user->departments()->get()->pluck('id');
        $roles = $user->roles()->get()->pluck('id');

        $user_action = DepartmentRoleMenu::whereIn('department_id', $departments)
            ->whereIn('role_id', $roles)
            ->where($action, $menu_id)
            ->first();
        if (!empty($user_action)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check user action for access resources.
     *
     * @param Int    $menu_id [Menu id]
     * @param String $action  [Action]
     *
     * @return Boolean
     */
    public function can_user_action($menu_id, $action, $id = null)
    {
        $isAllowed = false;
        if (empty(\Auth::user()) && empty(User::find($id)) ) {
            return false;
        }

        $user = \Auth::user();
        $profileUser = User::find($id);
        if (($user->hasRole('admin') || $user->is_super_admin)) {
            return true;
        }

        // if (($profileUser->hasRole('admin') || $profileUser->is_super_admin) && $user->id == $id) {
        //     return true;
        // }

        // if (($profileUser->hasRole('admin') || $profileUser->is_super_admin) && $user->id != $id) {
        //     return false;
        // }

        if($user->id == $id) {
            return true;
        }

        if($action == 'edit'){
            $_action = 'edited';
        } else {
            $_action = 'deleted';
        }
        if (!AdminHelper::can_action($menu_id, $_action)) {
            return false;
        }

        $permissionUsers = $this->getUserPermissions();
        if (empty($permissionUsers)) {
            $isAllowed = false;
        }

        if(!$user->permission) {
			$isAllowed = false;
        } else {
            if($user->permission == 'all') {
                foreach ($permissionUsers as $key => $value) {
                    if ($value->id == $user->id) {
                        $isAllowed = true;
                    }
                }
            } else {
                foreach ($permissionUsers as $key => $value) {
                    if ($value->id == $user->id) {
                        foreach (json_decode($user->permission) as $key1 => $value1) {
                            foreach ($value1 as $key => $value2) {
                                if ($value2 == $action) {
                                    $isAllowed = true;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $isAllowed;
    }

    /**
     * Get user permissions.
     *
     * @return Object
     */
    public function getUserPermissions()
    {
        $department_role_menu_table = config('core.acl.department_role_menu_table');
        $user_role_department = config('core.acl.user_role_department');
        $user_table = config('core.acl.users_table');

        // --
        // Get permission users
        return User::join($user_role_department, $user_table.'.id', '=', $user_role_department.'.user_id')
            ->join(
                $department_role_menu_table,
                function ($join) use ($department_role_menu_table, $user_role_department) {
                    $join->on($user_role_department.'.department_id', '=', $department_role_menu_table . '.department_id')
                        ->on($user_role_department.'.role_id', '=', $department_role_menu_table . '.role_id');
                }
            )->where($user_table . '.is_active', 1)
            ->where($department_role_menu_table.'.menu_id', 6)
            // ->where($user_role_department.'.role_id', 1)
            ->select(
                $user_table.'.id',
                $user_table.'.firstname',
                $user_table.'.lastname',
                $user_role_department.'.role_id',
                $user_role_department.'.department_id'
            )->get();
    }
}
