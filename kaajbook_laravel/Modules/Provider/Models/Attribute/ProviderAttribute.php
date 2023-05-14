<?php

namespace Modules\Provider\Models\Attribute;

/**
 * Trait ProviderAttribute
 *
 * The Attribute is Defined For Provider.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Provider
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait ProviderAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [First name]
     *
     * @return String
     */
    public function setFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = trim(ucwords($value));
    }

    /**
     * This mutator automatically convert the first character of each word to uppercase. 
     *
     * @param String $value [Last name]
     *
     * @return String
     */
    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = trim(ucwords($value));
    }
}
