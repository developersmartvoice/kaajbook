<?php

namespace Modules\Timesheet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Timesheet\Models\Attribute\TimesheetAttribute;
use Modules\Timesheet\Models\Relationship\TimesheetRelationship;

/**
 * Class Timesheet
 *
 * The Model is Defined for Timesheet.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Timesheet
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class Timesheet extends Model
{
    use SoftDeletes, TimesheetAttribute, TimesheetRelationship;

    const MODULE_NAME = 'Timesheet';

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
        'project_id',
        'module_id',
        'module_related_id',
        'start_time',
        'end_time',
        'decimal_time',
        'hour_time',
        'note',
        'created_user_id',
        'deleted_user_id'
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

        $this->table = config('core.acl.timesheets_table');
    }
}
