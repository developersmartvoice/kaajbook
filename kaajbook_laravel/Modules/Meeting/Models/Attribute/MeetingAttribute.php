<?php

namespace Modules\Meeting\Models\Attribute;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

/**
 * Trait MeetingAttribute
 *
 * The Attribute is Defined For Meeting.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Meeting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait MeetingAttribute
{
    /**
     * This mutator automatically convert start date format.
     *
     * @param String $value [Start date]
     *
     * @return Date
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = (new Carbon($value))->format('Y-m-d H:i:s');
    }

    /**
     * This mutator automatically convert end date format.
     *
     * @param String $value [End date]
     *
     * @return Date
     */
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = (new Carbon($value))->format('Y-m-d H:i:s');
    }
}
