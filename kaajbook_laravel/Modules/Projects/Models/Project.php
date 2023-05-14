<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Projects\Models\Attribute\ProjectAttribute;
use Modules\Projects\Models\Relationship\ProjectRelationship;

/**
 * Class Project
 *
 * The Model is Defined for Project.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Projects
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class Project extends Model
{
    use SoftDeletes, ProjectAttribute, ProjectRelationship;

    const MODULE_NAME = 'Project';

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
        'user_id',
        'generated_id',
        'project_name',
        'project_version',
        'client_id',
        'progress',
        'project_hours',
        'start_date',
        'end_date',
        'billing_type',
        'price_rate',
        'estimated_hours',
        'status',
        'demo_url',
        'description',
        'project_logo',
        'assign_to',
        'assign_members',
        'notes'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'start_date', 'end_date'];

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
        
        $this->table = config('core.acl.projects_table');
    }

    /**
     * Check user permission.
     *
     * @param String $query      [object]
     * @param Int    $project_id [Project Id]
     * @param Int    $user_id    [User Id]
     * @param String $method     [Action Name]
     *
     * @return void
     */
    public function scopeCheckPermission($query, $project_id, $user_id, $method)
    {
        $query->with(
            ['users' => function ($q) use ($project_id, $user_id, $method) {
                $q->select('id')
                    ->wherePivot('project_id', '=', $project_id)
                    ->wherePivot('user_id', '=', $user_id)
                    ->wherePivot($method, '=', true);
            }]
        );
    }
}
