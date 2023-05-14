<?php

namespace Modules\User\Models\User\Access;

/**
 * Trait UserAccess
 *
 * The Relationship is Defined For User.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait UserAccess
{
    /**
     * Checks if the user has a role by its slug.
     *
     * @param String $slug [Role slug]
     *
     * @return Boolean
     */
    public function hasRole($slug)
    {
        foreach ($this->roles as $role) {
            if ($role->slug == $slug) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks to see if user has array of roles.
     * All must return true
     *
     * @param Array   $roles    [Roles array]
     * @param Boolean $needsAll [Boolena]
     *
     * @return Boolean
     */
    public function hasRoles($roles, $needsAll = true)
    {
        //User has to possess all of the roles specified
        if ($needsAll) {
            $hasRoles = 0;
            $numRoles = count($roles);

            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    $hasRoles++;
                }
            }

            return $numRoles == $hasRoles;
        }

        //User has to possess one of the roles specified
        $hasRoles = 0;
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                $hasRoles++;
            }
        }

        return $hasRoles > 0;
    }

    /**
     * Check if user has a permission by its name or id.
     *
     * @param String $nameOrId [Permission name or id]
     *
     * @return Boolean
     */
    public function allow($nameOrId)
    {
        $permissionVal = str_replace(" ", "", strtolower($nameOrId). '_view');

        foreach ($this->permissions as $key => $permission) {
            foreach ($permission as $key => $perm) {
                if ($perm == $permissionVal) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check an array of permissions and whether or not all are required to continue
     *
     * @param Array   $permissions [Permissions array]
     * @param Boolean $needsAll    [Boolena]
     *
     * @return Boolean
     */
    public function allowMultiple($permissions, $needsAll = true)
    {
        //User has to possess all of the permissions specified
        if ($needsAll) {
            $hasPermissions = 0;
            $numPermissions = count($permissions);

            foreach ($permissions as $perm) {
                if ($this->allow($perm)) {
                    $hasPermissions++;
                }
            }

            return $numPermissions == $hasPermissions;
        }

        //User has to possess one of the permissions specified
        $hasPermissions = 0;
        foreach ($permissions as $perm) {
            if ($this->allow($perm)) {
                $hasPermissions++;
            }
        }

        return $hasPermissions > 0;
    }

    /**
     * Check permission.
     *
     * @param String $nameOrId [Permission name or id]
     *
     * @return Boolean
     */
    public function hasPermission($nameOrId)
    {
        return $this->allow($nameOrId);
    }

    /**
     * Check permissions.
     *
     * @param Array   $permissions [Permissions array]
     * @param Boolean $needsAll    [Boolean]
     *
     * @return Boolean
     */
    public function hasPermissions($permissions, $needsAll = true)
    {
        return $this->allowMultiple($permissions, $needsAll);
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param Array $role [Role array]
     *
     * @return void
     */
    public function attachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
    }

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param Array $role [Role array]
     *
     * @return void
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
    }

    /**
     * Attach multiple roles to a user.
     *
     * @param Array $roles [Roles array]
     *
     * @return void
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * Detach multiple roles from a user.
     *
     * @param Array $roles [Roles array]
     *
     * @return void
     */
    public function detachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }

    /**
     * Detach all roles from a user.
     *
     * @return Response
     */
    public function detachAllRoles()
    {
        $this->roles = null;
        return $this->roles()->detach();
    }
}
