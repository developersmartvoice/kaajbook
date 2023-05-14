<?php

namespace Modules\Appointments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Appointments\Repositories\AppointmentRecurrenceRepository;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class AppointmentRecurrenceController
 *
 * Appointment Recurrence status change.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Announcement
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2022 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AppointmentRecurrenceController extends Controller
{
    protected $appointmentRecurrenceRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param AppointmentRecurrenceRepository $appointmentRecurrenceRepo [object]
     *
     * @return void
     */
    public function __construct(AppointmentRecurrenceRepository $appointmentRecurrenceRepo)
    {
        $this->appointmentRecurrenceRepo = $appointmentRecurrenceRepo;
    }

    /**
     * Change appointment recurrence status.
     *
     * @param Request $request [Request for change appointment status]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(71, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->appointmentRecurrenceRepo->changeStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
