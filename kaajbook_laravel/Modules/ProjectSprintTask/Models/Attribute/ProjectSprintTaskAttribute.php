<?php

namespace Modules\ProjectSprintTask\Models\Attribute;

/**
 * Trait ProjectSprintTaskAttribute
 *
 * The Attribute is Defined for Project Sprint Task.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectSprintTask
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait ProjectSprintTaskAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [Sprint task name]
     *
     * @return String
     */
    public function setTaskNameAttribute($value)
    {
        $this->attributes['task_name'] = trim(ucwords($value));
    }

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
}
