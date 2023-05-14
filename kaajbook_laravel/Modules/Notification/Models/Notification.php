<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 *
 * The Model is Defined for Notification.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Notification
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2022 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.2@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.2
 */
class Notification extends Model
{
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
        'route',
        'route_related_id',
        'title',
        'message',
    ];

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

        $this->table = config('core.acl.notifications_table');
    }
}
