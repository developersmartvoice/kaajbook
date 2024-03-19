<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Task\Models\Attribute\TaskAttribute;
use Modules\Task\Models\Relationship\TaskRelationship;

/**
 * Class Task
 *
 * The Model is Defined for Task.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Task
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class Task extends Model
{
    use SoftDeletes, TaskRelationship, TaskAttribute;

    const MODULE_NAME = 'Task';

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
        'name',
        'parent_task_id',
        'generated_id',
        'priority',
        'project_id',
        'project_version',
        'planned_start_date',
        'planned_end_date',
        'task_start_date',
        'task_end_date',
        'estimated_hours',
        'status',
        'task_logo',
        'progress',
        'created_by',
        'description',
        'assign_to',
        'notes',
        'order'
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
    protected $dates = ['deleted_at', 'task_start_date', 'task_end_date'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_overdue'];

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

        $this->table = config('core.acl.task_table');
    }
}
