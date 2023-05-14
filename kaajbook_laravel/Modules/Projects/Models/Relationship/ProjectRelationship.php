<?php

namespace Modules\Projects\Models\Relationship;

/**
 * Trait ProjectRelationship
 *
 * The Relationship is Defined for Project.
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
trait ProjectRelationship
{
    /**
     * Remove project task, defect, incident, sprint comment and attachments.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($project) {
                $project->comments()->delete();
                $project->attachments()->delete();
                $project->tasks()->delete();
                $project->defects()->delete();
                $project->incidents()->delete();
                $project->sprints()->delete();
                $project->timesheets()->delete();
            }
        );
    }

    /**
     * Many-to-Many relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            config('core.acl.user'),
            config('core.acl.project_user_table')
        );
    }

    /**
     * Has-One relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function createdUser()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'user_id');
    }

    /**
     * One-to-Many relations with comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function comments()
    {
        return $this->hasMany(
            config('core.acl.project_comment_model'),
            'project_id'
        );
    }

    /**
     * One-to-Many relations with comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function attachments()
    {
        return $this->hasMany(
            config('core.acl.project_attachment_model'),
            'project_id'
        );
    }

    /**
     * One To Many relationship with task
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function tasks()
    {
        return $this->hasMany(config('core.acl.task_model'), 'project_id');
    }

    /**
     * One To Many relationship with defect
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function defects()
    {
        return $this->hasMany(config('core.acl.defects_model'), 'project_id');
    }

    /**
     * One To Many relationship with incidents
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function incidents()
    {
        return $this->hasMany(config('core.acl.incidents_model'), 'project_id');
    }

    /**
     * One To Many relationship with sprints
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function sprints()
    {
        return $this->hasMany(
            config('core.acl.project_planner_sprint_model'),
            'project_id'
        );
    }

    /**
     * Has-One relations with clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function clients()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'client_id');
    }

    /**
     * One To Many relationship with timesheets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function timesheets()
    {
        return $this->hasMany(config('core.acl.timesheet_model'), 'project_id');
    }

    /**
     * Has-One relations with team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function team()
    {
        return $this->hasOne(config('core.acl.team_model'), 'id', 'assign_to');
    }
}
