<?php

namespace Modules\Announcement\Models\Relationship;

/**
 * Trait AnnouncementRelationship
 *
 * The Relationship is Defined For AnnouncementRelationship.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\AnnouncementRelationship
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait AnnouncementRelationship
{
    /**
     * One-to-Many relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('core.acl.user'));
    }
}
