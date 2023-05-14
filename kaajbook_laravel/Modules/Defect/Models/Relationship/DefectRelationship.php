<?php

namespace Modules\Defect\Models\Relationship;

/**
 * Trait DefectRelationship
 *
 * The Relationship is Defined For Defect.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Defect
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait DefectRelationship
{
    /**
     * Remove defect comment, attachments and history.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($defect) {
                $defect->comments()->delete();
                $defect->attachments()->delete();
                $defect->history()->delete();
            }
        );
    }

    /**
     * Many-to-Many relations with users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            config('core.acl.user'),
            config('core.acl.defects_user_table'),
            'defect_id',
            'user_id'
        );
    }

    /**
     * One-to-One relations with created user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function createdUser()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'create_user_id');
    }

    /**
     * One-to-One relations with assign user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function assignUser()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'assign_member');
    }

    /**
     * One-to-Many relations with defect history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function history()
    {
        return $this->hasMany(config('core.acl.defects_history_model'));
    }

    /**
     * One-to-Many relations with defect comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function comments()
    {
        return $this->hasMany(config('core.acl.defect_comment_model'), 'defect_id');
    }

    /**
     * One-to-Many relations with attachments comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function attachments()
    {
        return $this->hasMany(
            config('core.acl.defect_attachment_model'),
            'defect_id'
        );
    }

    /**
     * One-to-One relations with project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function project()
    {
        return $this->hasOne(config('core.acl.project_model'), 'id', 'project_id');
    }

    /**
     * One-to-One relations with team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function team()
    {
        return $this->hasOne(
            config('core.acl.team_model'),
            'id',
            'assigned_group_id'
        );
    }
}
