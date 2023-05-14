<?php

namespace Modules\Appointments\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AppointmentRecurrence
 *
 * The model is defined for Appointment Recurrence.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Appointment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2022 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AppointmentRecurrence extends Model
{
    const MODULE_NAME = 'AppointmentRecurrence';

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
        'appointment_id',
        'start_date_time',
        'end_date_time',
        'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date_time', 'end_date_time'];

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
     * Instantiate a new model instance.
     *
     * @param Array $attributes [object]
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('core.acl.appointment_recurrence_table');
    }
}
