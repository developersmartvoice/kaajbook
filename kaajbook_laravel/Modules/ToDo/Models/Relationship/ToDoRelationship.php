<?php

namespace Modules\ToDo\Models\Relationship;

use Modules\User\Models\User\User;

/**
 * Trait ToDoRelationship
 *
 * The Relationship is Defined For ToDo.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\ToDo
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait ToDoRelationship
{
    /**
     * Many-to-Many relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assigned()
    {
        return $this->belongsToMany(
            config('core.acl.user'),
            config('core.acl.todo_user_pivot'),
            'todo_id',
            'user_id'
        );
    }
}
