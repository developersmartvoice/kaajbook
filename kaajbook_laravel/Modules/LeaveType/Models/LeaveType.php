<?php

namespace Modules\LeaveType\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\LeaveType\Models\Attribute\LeaveTypeAttribute;
use Modules\LeaveType\Models\Relationship\LeaveTypeRelationship;

/**
 * Class LeaveType
 *
 * The Model is Defined for LeaveType.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\LeaveType
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class LeaveType extends Model
{
    use LeaveTypeAttribute, LeaveTypeRelationship, SoftDeletes;

    const MODULE_NAME = 'LeaveType';

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
        'leave_type',
        'no_of_leaves',
        'color'
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
        
        $this->table = config('core.acl.leave_types_table');
    }
}
