<?php

namespace Modules\Estimate\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Estimate\Models\Attribute\EstimateAttribute;
use Modules\Estimate\Models\Relationship\EstimateRelationship;

/**
 * Class Estimate
 *
 * The Model is Defined for Estimate.
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
class Estimate extends Model
{
    use EstimateAttribute, EstimateRelationship;

    const MODULE_NAME = 'Estimate';

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
        'user_id',
        'client_id',
        'invoice_id',
        'estimate_number',
        'estimate_date',
        'valid_till',
        'sub_total',
        'total_tax',
        'discount_type',
        'discount',
        'total_discount',
        'adjustment',
        'total_amount',
        'reference',
        'note',
        'status'
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
    protected $dates = ['estimate_date', 'valid_till'];

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
        
        $this->table = config('core.acl.estimates_table');
    }
}
