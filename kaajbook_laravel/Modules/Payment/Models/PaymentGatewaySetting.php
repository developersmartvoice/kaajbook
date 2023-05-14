<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentGatewaySetting
 *
 * The Migrations is Defined for Payment Gateway Setting.
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
class PaymentGatewaySetting extends Model
{
    const MODULE_NAME = 'PaymentGatewaySetting';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paypal_checkout_label',
        'paypal_checkout_client_id',
        'paypal_checkout_status',
        'stripe_label',
        'stripe_api_key',
        'stripe_secret_key',
        'stripe_status'
    ];

    /**
     * Instantiate a new model instance.
     *
     * @param Array $attributes [object]
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->table = config('core.acl.payment_gateway_settings_table');
    }
}
