<?php

namespace Modules\DefectComment\Models\Relationship;

/**
 * Trait DefectCommentRelationship
 *
 * The Relationship is Defined for Defect Comment.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\DefectComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait DefectCommentRelationship
{
    /**
     * One-to-Many relations with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
