<?php

namespace Modules\Payment\Models\Attribute;

/**
 * Trait PaymentMethodAttribute
 *
 * The Attribute is Defined for Payment Method.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Payment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
trait PaymentMethodAttribute
{
    /**
     * This mutator automatically convert the first character of each word to uppercase.
     *
     * @param String $value [Value]
     *
     * @return String
     */
    public function setMethodNameAttribute($value)
    {
        $this->attributes['method_name'] = trim(ucwords($value));
    }

    /**
     * This mutator automatically format amount.
     *
     * @param String $value [Value]
     *
     * @return String
     */
    // public function getAmountAttribute($value)
    // {
    //     return number_format($value, 2);
    // }

    /**
     * This mutator automatically encrypt id.
     *
     * @return String.
     */
    public function getSecretIdAttribute()
    {
        return encrypt($this->id);
    }
}
