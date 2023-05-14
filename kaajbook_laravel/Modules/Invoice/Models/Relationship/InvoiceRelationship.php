<?php

namespace Modules\Invoice\Models\Relationship;

/**
 * Trait InvoiceRelationship
 *
 * The Relationship is Defined for Invoice.
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
trait InvoiceRelationship
{
    /**
     * Remove invoice items.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($invoice) {
                $invoice->items()->delete();
                $invoice->payments()->delete();
            }
        );
    }

    /**
     * One To Many relationship with invoice items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function items()
    {
        return $this->hasMany(config('core.acl.invoice_item_model'), 'invoice_id');
    }

    /**
     * Has-One relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function user()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'user_id')
            ->select(['id', 'firstname', 'lastname', 'avatar', 'email']);
    }

    /**
     * Has-One relations with clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function client()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'client_id')
            ->select(['id', 'firstname', 'lastname', 'avatar', 'email', 'address', 'mobile','phone']);
    }

    /**
     * One To Many relationship with payments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function payments()
    {
        return $this->hasMany(config('core.acl.payment_model'), 'invoice_id');
    }

    /**
     * Has-One relations with project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function project()
    {
        return $this->hasOne(config('core.acl.project_model'), 'id', 'project_id')
            ->select(['id', 'generated_id', 'project_name']);
    }
}
