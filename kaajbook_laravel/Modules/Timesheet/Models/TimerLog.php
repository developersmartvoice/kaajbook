<?php

namespace Modules\Timesheet\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TimerLog
 *
 * The Model is Defined for Timer Log.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Timesheet
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.1@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.1
 */
class TimerLog extends Model
{
    const MODULE_NAME = 'TimerLog';

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
        'project_id',
        'reference_id',
        'is_task_timer',
        'is_defect_timer',
        'is_incident_timer',
        'start_time'
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

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

        $this->table = config('core.acl.timer_logs_table');
    }
}
