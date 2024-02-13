<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Invoice\Models\Attribute\InvoiceAttribute;
use Modules\Invoice\Models\Relationship\InvoiceRelationship;

/**
 * Class Invoice
 *
 * The Model is Defined for Invoice.
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
class Invoice extends Model
{
    use InvoiceAttribute, InvoiceRelationship;

    const MODULE_NAME = 'Invoice';

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
        'parent_id',
        'user_id',
        'client_id',
        'project_id',
        'estimate_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'sub_total',
        'total_tax',
        'discount_type',
        'discount',
        'total_discount',
        'adjustment',
        'total_amount',
        'total_due_amount',
        'reference',
        'note',
        'invoice_header_information_text',
        'recurrence',
        'recurrence_occurrences',
        'recurrence_pattern',
        'is_recurrence_generated',
        'last_overdue_reminder',
        'status',
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['invoice_date', 'due_date'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['secret_id'];

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

        $this->table = config('core.acl.invoices_table');
    }
}
