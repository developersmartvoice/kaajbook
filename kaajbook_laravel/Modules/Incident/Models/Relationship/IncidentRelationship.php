<?php

namespace Modules\Incident\Models\Relationship;

/**
 * Trait IncidentRelationship
 *
 * The Relationship is Defined for Incident.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Incident
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait IncidentRelationship
{
    /**
     * Remove incident comment, attachments and history.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($incident) {
                $incident->comments()->delete();
                $incident->attachments()->delete();
                $incident->history()->delete();
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
            config('core.acl.incident_user_table'),
            'incident_id',
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
     * Has-One relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function assignUser()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'assign_to');
    }

    /**
     * One-to-Many relations with incident history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function history()
    {
        return $this->hasMany(config('core.acl.incident_history_model'));
    }

    /**
     * One-to-Many relations with comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function comments()
    {
        return $this->hasMany(
            config('core.acl.incident_comment_model'),
            'incident_id'
        );
    }

    /**
     * One-to-Many relations with attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function attachments()
    {
        return $this->hasMany(
            config('core.acl.incident_attachment_model'),
            'incident_id'
        );
    }

    /**
     * One-to-One relations with projects.
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
