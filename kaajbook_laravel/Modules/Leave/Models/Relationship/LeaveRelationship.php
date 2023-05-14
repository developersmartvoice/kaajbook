<?php

namespace Modules\Leave\Models\Relationship;

/**
 * Trait LeaveRelationship
 *
 * The Relationship is Defined for Leave.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\Leave
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
trait LeaveRelationship
{
    /**
     * Has-One relations with leave type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function leaveType()
    {
        return $this->hasOne(config('core.acl.leave_type_model'), 'id', 'leave_type_id');
    }

    /**
     * Has-One relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function user()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'user_id');
    }

    /**
     * Many-to-Many relations with attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attachments()
    {
        return $this->hasMany(config('core.acl.leave_attachment_model'), 'leave_id');
    }
}
