<?php

namespace Modules\User\Models\Role;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Role\Attribute\RoleAttribute;
use Modules\User\Models\Role\Relationship\RoleRelationship;

/**
 * Class Role
 *
 * The Model is Defined for Role.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class Role extends Model
{
    use RoleAttribute, RoleRelationship;
    const MODULE_NAME = 'Role';
    
    /**
     * The database table used by the model.
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
    'name',
    'slug',
    'description',
    'status'
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
        
        $this->table = config('core.acl.roles_table');
    }
}
