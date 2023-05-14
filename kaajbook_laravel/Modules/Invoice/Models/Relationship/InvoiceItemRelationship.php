<?php

namespace Modules\Invoice\Models\Relationship;

/**
 * Trait InvoiceItemRelationship
 *
 * The Relationship is Defined for Invoice Item Taxes.
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
trait InvoiceItemRelationship
{
    /**
     * Remove invoice item texes.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($invoiceItem) {
                $invoiceItem->taxes()->delete();
            }
        );
    }

    /**
     * Many-to-Many relations with tax.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxes()
    {
        return $this->belongsToMany(
            config('core.acl.tax_model'),
            config('core.acl.invoice_item_taxes_table')
        )->withPivot("item_tax");
    }
}
