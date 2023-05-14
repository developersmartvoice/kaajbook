<?php

namespace Modules\Task\Models\Relationship;

/**
 * Trait TaskRelationship
 *
 * The Relationship is Defined for Task.
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
trait TaskRelationship
{
    /**
     * Remove task comment and attachments.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($task) {
            $task->comments()->delete();
            $task->attachments()->delete();
        });
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
            config('core.acl.task_user_table')
        );
    }

    /**
     * Has-One relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function assignUser()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'assign_to');
    }

    /**
     * Has-One relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function createdUser()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'created_by');
    }
    
    /**
     * Many-to-Many relations with projects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function project()
    {
        return $this->belongsTo(config('core.acl.project_model'), 'id');
    }

    /**
     * One-To-One realtion with project
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function project1()
    {
        return $this->hasOne(config('core.acl.project_model'), 'id', 'project_id');
    }

    /**
     * One-to-Many relations with comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function comments()
    {
        return $this->hasMany(config('core.acl.task_comment_model'), 'task_id');
    }

    /**
     * One-to-Many relations with attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function attachments()
    {
        return $this->hasMany(config('core.acl.task_attachment_model'), 'task_id');
    }

    /**
     * One-to-Many relations with tasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function subTasks()
    {
        return $this->hasMany(config('core.acl.task_model'), 'parent_task_id','id');
    }
}
