<?php

namespace Modules\ProjectSprintTask\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ProjectSprintTask\Models\Attribute\ProjectSprintTaskAttribute;
use Modules\ProjectSprintTask\Models\Relationship\ProjectSprintTaskRelationship;

/**
 * Class ProjectSprintTask
 *
 * The Model is Defined for Project Sprint Task.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectSprintTask
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectSprintTask extends Model
{
    use SoftDeletes, ProjectSprintTaskRelationship, ProjectSprintTaskAttribute;

    const MODULE_NAME = 'ProjectSprintTask';

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
        'project_sprint_id',
        'project_id',
        'name',
        'start_date',
        'end_date',
        'status',
        'description',
        'type',
        'estimated_hours',
        'assign_to'
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
        
        $this->table = config('core.acl.project_sprint_tasks_table');
    }
}
