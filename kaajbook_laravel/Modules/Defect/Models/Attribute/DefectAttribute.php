<?php

namespace Modules\Defect\Models\Attribute;

use Carbon\Carbon;

/**
 * Trait DefectAttribute
 *
 * The Attribute is Defined for Defect.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Defect
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait DefectAttribute
{
    /**
     * This mutator automatically set start date format.
     *
     * @param Date $value [Start date]
     *
     * @return Date
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = ($value == null) ? null : date('Y-m-d', strtotime($value));
    }

    /**
     * This mutator automatically set end date format.
     *
     * @param Date $value [End date]
     *
     * @return Date
     */
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = ($value == null) ? null : date('Y-m-d', strtotime($value));
    }

    /**
     * This mutator automatically convert string to number.
     *
     * @param String $value [Assign Member]
     *
     * @return Int.
     */
    public function getAssignMemberAttribute($value)
    {
        return ($value) ? intval($value) : null;
    }

    /**
     * This mutator automatically decide the defect is overdue or not.
     *
     * @return String
     */
    public function getIsOverdueAttribute()
    {
        if (in_array($this->status, [1, 3, 4, 6]) && $this->end_date < date('Y-m-d')) {
            $endDate = Carbon::parse($this->end_date);
            return Carbon::parse(Carbon::now())->diffInDays($endDate);
        }
        return false;
    }
}
