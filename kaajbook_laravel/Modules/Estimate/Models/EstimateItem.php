<?php

namespace Modules\Estimate\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Estimate\Models\Relationship\EstimateItemRelationship;

/**
 * Class EstimateItem
 *
 * The Model is Defined for Estimate Item.
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
class EstimateItem extends Model
{
    use EstimateItemRelationship;
    
    const MODULE_NAME = 'EstimateItem';

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
        'item_name',
        'item_description',
        'item_unit',
        'unit_price',
        'quantity',
        'total_item_amount'
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
        
        $this->table = config('core.acl.estimate_items_table');
    }
}
