<?php

namespace Modules\Appointments\Repositories;

use Modules\Appointments\Models\Appointment;
use Modules\Appointments\Models\AppointmentRecurrence;

/**
 * Class AppointmentRecurrenceRepository
 *
 * Appointment Recurrence CRUD functionality.
 *
 * Appointment Recurrence status change.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Appointments
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2022 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AppointmentRecurrenceRepository
{
    /**
     * Changed appointment recurrence status.
     *
     * @param Request $request [Request for change appointment recurrence status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeStatus($request, $id)
    {
        $input['status'] = $request->get('status');
        $appointmentRecurrence = AppointmentRecurrence::findOrFail($id);
        $appointment = Appointment::findOrFail($appointmentRecurrence->appointment_id);
        if ($appointmentRecurrence) {
            $appointmentRecurrence->fill($input)->save();
            // --
            // Add activity
            createUserActivity(
                AppointmentRecurrence::MODULE_NAME,
                $appointmentRecurrence->id,
                $request->method(),
                $appointment->title,
                $request->ip(),
                $appointmentRecurrence->id,
                true
            );
        }
        return true;
    }
}
