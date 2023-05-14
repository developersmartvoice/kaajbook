<?php

namespace Modules\LeaveType\Models\Attribute;

/**
 * Trait LeaveTypeAttribute
 *
 * The Attribute is Defined for Leave type.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\LeaveType
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
trait LeaveTypeAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase.
     *
     * @param String $value [Leave type name]
     *
     * @return String
     */
    public function setLeaveTypeAttribute($value)
    {
        $this->attributes['leave_type'] = trim(ucwords($value));
    }
}
