<?php

namespace Modules\Holiday\Models\Attribute;

use Carbon\Carbon;

/**
 * Trait HolidayAttribute
 *
 * The Attribute is Defined For Holiday.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Holiday
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait HolidayAttribute
{
    /**
     * This mutator automatically set date format.
     *
     * @param Date $value [Date]
     *
     * @return Date
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = (new Carbon($value))->format('Y-m-d');
    }

    /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [Field label]
     *
     * @return String
     */
    public function setEventNameAttribute($value)
    {
        $this->attributes['event_name'] = trim(ucwords($value));
    }
}
