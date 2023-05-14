<?php

namespace Modules\Payment\Models\Relationship;

/**
 * Trait PaymentRelationship
 *
 * The Relationship is Defined for Payment.
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
trait PaymentRelationship
{
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
     * Has-One relations with invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function invoice()
    {
        return $this->hasOne(config('core.acl.invoice_model'), 'id', 'invoice_id')
            ->select(['id', 'user_id', 'invoice_number', 'invoice_date', 'total_amount', 'total_due_amount']);
    }
}
