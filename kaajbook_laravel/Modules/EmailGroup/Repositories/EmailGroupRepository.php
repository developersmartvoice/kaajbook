<?php

namespace Modules\EmailGroup\Repositories;

use Modules\EmailGroup\Models\EmailGroup;

/**
 * Class EmailGroupRepository
 *
 * Email Group insert, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\EmailGroup
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EmailGroupRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return EmailGroup::with('templates')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function getEmailGroup()
    {
        return EmailGroup::pluck('email_group_name', 'id');
    }

    /**
     * Get the specified resource from storage
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function getById($id)
    {
        return EmailGroup::findOrFail($id);
    }
}
