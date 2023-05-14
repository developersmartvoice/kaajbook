<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoiceSetting
 *
 * The Model is Defined for Invoice Setting.
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
class InvoiceSetting extends Model
{
    const MODULE_NAME = 'InvoiceSetting';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_prefix',
        'due_after',
        'invoice_logo',
        'template',
        'gst_number',
        'show_gst_number',
        'client_note',
        'terms_conditions',
        'auto_remind',
        'auto_remind_after',
        'payment_prefix'
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

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
        
        $this->table = config('core.acl.invoice_settings_table');
    }
}
