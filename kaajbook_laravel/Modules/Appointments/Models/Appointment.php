<?php

namespace Modules\Appointments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Appointments\Models\Relationship\AppointmentRelationship;
use Modules\Appointments\Models\Attribute\AppointmentAttribute;

/**
 * Class Appointment
 *
 * The model is defined for Appointment.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Appointment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class Appointment extends Model
{
    use SoftDeletes, AppointmentAttribute, AppointmentRelationship;

    const MODULE_NAME = 'Appointments';

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
        'generated_id',
        'title',
        'client_id',
        'provider_id',
        'attendees',
        'start_date_time',
        'end_date_time',
        'location',
        'note',
        'recurrence', 
        'recurrence_occurrences', 
        'recurrence_pattern',
        'status',
        'send_confirmation'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'start_date_time', 'end_date_time'];

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

        $this->table = config('core.acl.appointment_table');
    }
}
