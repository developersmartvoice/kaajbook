<?php

namespace Modules\ProjectPlannerSprint\Models\Relationship;

/**
 * Trait ProjectPlannerSprintRelationship
 *
 * The Relationship is Defined for Project Planner Sprint.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectPlannerSprint
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait ProjectPlannerSprintRelationship
{
    /**
     * Remove sprint tasks.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($sprint) {
                $sprint->tasks()->delete();
            }
        );
    }

    /**
     * Many-to-Many relations with sprint members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sprintMembers()
    {
        return $this->belongsToMany(
            config('auth.providers.users.model'),
            config('core.acl.project_sprint_members_table'),
            'project_sprint_id',
            'user_id'
        );
    }

    /**
     * One To Many relationship with tasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tasks()
    {
        return $this->hasMany(
            config('core.acl.project_planner_sprint_task_model'),
            'project_sprint_id'
        )->whereNotIn('status', [4]);
    }

    /**
     * One-to-One relations with project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function project()
    {
        return $this->belongsTo(config('core.acl.project_model'));
    }
}
