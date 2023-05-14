<?php

namespace Modules\Invoice\Models\Attribute;

/**
 * Trait InvoiceAttribute
 *
 * The Attribute is Defined for Invoice.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Invoice
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
trait InvoiceAttribute
{
    /**
     * This mutator automatically set invoice date format.
     *
     * @param String $value [Invoice date]
     *
     * @return String
     */
    public function setInvoiceDateAttribute($value)
    {
        $this->attributes['invoice_date'] = date('Y-m-d', strtotime($value));
    }

    /**
     * This mutator automatically set due date format.
     *
     * @param String $value [Due date]
     *
     * @return String
     */
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = date('Y-m-d', strtotime($value));
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
