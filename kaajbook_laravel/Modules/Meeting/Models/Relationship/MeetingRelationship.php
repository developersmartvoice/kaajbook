<?php

namespace Modules\Meeting\Models\Relationship;

/**
 * Trait MeetingRelationship
 *
 * The Relationship is Defined For Meeting.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Meeting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait MeetingRelationship
{
    /**
     * One-to-One relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(
            config('auth.providers.users.model'),
            'organizer_id'
        );
    }

    /**
     * One-to-One relations with client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function client()
    {
        return $this->belongsTo(
            config('core.acl.user'),
            'client_id'
        );
    }

    /**
     * One-to-One relations with project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function project()
    {

        return $this->belongsTo(config('core.acl.project_model'), 'project_id')
            ->select('id', 'generated_id', 'project_name');
    }

    /**
     * Many-to-Many relations with members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(
            config('auth.providers.users.model'),
            config('core.acl.meeting_members'),
            'meeting_id',
            'user_id'
        );
    }
}
