<?php

namespace Modules\Announcement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Announcement\Models\Attribute\AnnouncementAttribute;
use Modules\Announcement\Models\Relationship\AnnouncementRelationship;

/**
 * Class Announcement
 *
 * The model is Defined for Announcement.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Announcement
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class Announcement extends Model
{
    use AnnouncementRelationship, AnnouncementAttribute, SoftDeletes;

    const MODULE_NAME = 'Announcement';
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
        'user_id',
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'all_client'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        
        $this->table = config('core.acl.announcements_table');
    }
}
