<?php

namespace Modules\Leave\Models\Attribute;

/**
 * Trait LeaveAttribute
 *
 * The Attribute is Defined for Leave.
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
trait LeaveAttribute
{
    /**
     * This mutator automatically set leave date format.
     *
     * @param Date $value [Leave date]
     *
     * @return Date
     */
    public function setLeaveDateAttribute($value)
    {
        $this->attributes['leave_date'] = ($value == null) ? null : date('Y-m-d', strtotime($value));
    }
}
