<?php

namespace Modules\Item\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Models\Attribute\ItemAttribute;
use Modules\Item\Models\Relationship\ItemRelationship;

/**
 * Class Item
 *
 * The Model is Defined for Item.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Item
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class Item extends Model
{
    use SoftDeletes, ItemAttribute, ItemRelationship;

    const MODULE_NAME = 'Item';

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
        'name',
        'price',
        'description',
        'unit'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        
        $this->table = config('core.acl.items_table');
    }
}
