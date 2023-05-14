<?php

namespace Modules\Estimate\Models\Attribute;

/**
 * Trait EstimateAttribute
 *
 * The Attribute is Defined for Estimate.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Estimate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
trait EstimateAttribute
{
    /**
     * This mutator automatically set estimate date format.
     *
     * @param String $value [Estimate date]
     *
     * @return String
     */
    public function setEstimateDateAttribute($value)
    {
        $this->attributes['estimate_date'] = date('Y-m-d', strtotime($value));
    }

    /**
     * This mutator automatically set valid till date format.
     *
     * @param String $value [Valid till date]
     *
     * @return String
     */
    public function setValidTillAttribute($value)
    {
        $this->attributes['valid_till'] = date('Y-m-d', strtotime($value));
    }

    /**
     * This mutator automatically set adjustment value.
     *
     * @param String $value [Adjustment]
     *
     * @return String
     */
    public function setAdjustmentAttribute($value)
    {
        $this->attributes['adjustment'] = ($value == null) ? 0 : $value;
    }

    /**
     * This mutator automatically set discount value.
     *
     * @param String $value [Discount]
     *
     * @return String
     */
    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = ($value == null) ? 0 : $value;
    }

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
