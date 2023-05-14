<?php

namespace Modules\Provider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Provider\Models\Attribute\ProviderAttribute;
use Modules\Provider\Models\Relationship\ProviderRelationship;

/**
 * Class Provider
 *
 * The model is Defined for Provider.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Provider
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class Provider extends Model
{
    use SoftDeletes, ProviderAttribute, ProviderRelationship;

    const MODULE_NAME = 'Provider';
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
        'firstname',
        'lastname',
        'email',
        'color',
        'status',
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
        
        $this->table = config('core.acl.providers_table');
    }
}
