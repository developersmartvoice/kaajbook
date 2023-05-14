<?php

namespace Modules\Client\Models\Attribute;

/**
 * Trait Client
 *
 * The Attribute is Defined For Client.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Client
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait ClientAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [Company name]
     *
     * @return String
     */
    public function setCompanyNameAttribute($value)
    {
        $this->attributes['company_name'] = trim(ucwords($value));
    }

    /**
     * This mutator automatically set convert country to number.
     *
     * @param String $value [Country]
     *
     * @return Date.
     */
    public function getCompanyCountryAttribute($value)
    {
        return ($value) ? $value : null;
    }
}
