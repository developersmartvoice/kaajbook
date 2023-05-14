<?php

namespace Modules\Appointments\Models\Relationship;

/**
 * Trait AppointmentRelationship
 *
 * The relationship is defined for appointment.
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
trait AppointmentRelationship
{
    /**
     * Has-One relations with clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function clients()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'client_id');
    }

    /**
     * Has-One relations with providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function providers()
    {
        return $this->hasOne(config('core.acl.provider_model'), 'id', 'provider_id');
    }

    /**
     * Has-One relations with requesters.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function requesters()
    {
        return $this->hasOne(config('core.acl.user'), 'id', 'user_id');
    }

    /**
     * One-to-Many relations with recurrences.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HashMany
     */
    public function recurrences()
    {
        return $this->hasMany(
            config('core.acl.appointment_recurrence_model'),
            'appointment_id'
        );
    }
}
