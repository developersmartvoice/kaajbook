<?php

namespace Modules\Installer\Helpers;

/**
 * Class PermissionsChecker
 *
 * Permission helper to check permission.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Installer
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class PermissionsChecker
{
    protected $results = [];

    /**
     * Set the result array permissions and errors.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->results['permissions'] = [];

        $this->results['errors'] = null;
    }

    /**
     * Check for the folders permissions.
     *
     * @param Array $folders [Folders array]
     *
     * @return Array
     */
    public function check(array $folders)
    {
        foreach ($folders as $folder => $permission) {
            if (!($this->_getPermission($folder) >= $permission)) {
                $this->_addFileAndSetErrors($folder, $permission, false);
            } else {
                $this->_addFile($folder, $permission, true);
            }
        }

        return $this->results;
    }

    /**
     * Get a folder permission.
     *
     * @param String $folder [Folder name]
     *
     * @return String
     */
    private function _getPermission($folder)
    {
        clearstatcache(null, base_path($folder));
        return substr(sprintf('%o', fileperms(base_path($folder))), -4);
    }

    /**
     * Add the file to the list of results.
     *
     * @param String  $folder     [Folder name]
     * @param String  $permission [Folder permission]
     * @param Boolean $isSet      [Boolean for set]
     *
     * @return void
     */
    private function _addFile($folder, $permission, $isSet)
    {
        array_push(
            $this->results['permissions'],
            [
            'folder' => $folder,
            'permission' => $permission,
            'isSet' => $isSet
            ]
        );
    }

    /**
     * Add the file and set the errors.
     *
     * @param String  $folder     [Folder name]
     * @param String  $permission [Folder permission]
     * @param Boolean $isSet      [Boolean for set]
     *
     * @return void
     */
    private function _addFileAndSetErrors($folder, $permission, $isSet)
    {
        $this->_addFile($folder, $permission, $isSet);

        $this->results['errors'] = true;
    }
}
