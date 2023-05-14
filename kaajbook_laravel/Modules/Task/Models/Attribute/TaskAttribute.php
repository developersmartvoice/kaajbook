<?php

namespace Modules\Task\Models\Attribute;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Trait TaskAttribute
 *
 * The Attribute is Defined for Task.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Task
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait TaskAttribute
{
    /**
     * This mutator automatically set planned start date format.
     *
     * @param Date $value [Planned start date]
     *
     * @return Date
     */
    public function setPlannedStartDateAttribute($value)
    {
        $this->attributes['planned_start_date'] = ($value == null) ? null : date('Y-m-d', strtotime($value));
    }

    /**
     * This mutator automatically set planned end date format.
     *
     * @param Date $value [Planned end date]
     *
     * @return Date
     */
    public function setPlannedEndDateAttribute($value)
    {
        $this->attributes['planned_end_date'] = ($value == null) ? null : date('Y-m-d', strtotime($value));
    }

    /**
     * This mutator automatically set task start date format.
     *
     * @param Date $value [Task start date]
     *
     * @return Date
     */
    public function setTaskStartDateAttribute($value)
    {
        $this->attributes['task_start_date'] = ($value == null) ? null : date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * This mutator automatically set task end date format.
     *
     * @param Date $value [Task end date]
     *
     * @return Date
     */
    public function setTaskEndDateAttribute($value)
    {
        $this->attributes['task_end_date'] = ($value == null) ? null : date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * This mutator automatically decide the task is overdue or not.
     *
     * @return String
     */
    public function getIsOverdueAttribute()
    {
        if (in_array($this->status, [1, 2, 3, 4]) && $this->task_end_date < date('Y-m-d')) {
            $endDate = Carbon::parse($this->task_end_date);
            return Carbon::parse(Carbon::now())->diffInDays($endDate);
        }
        return false;
    }
}
