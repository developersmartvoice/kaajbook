<?php

namespace Modules\Appointments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Helper\Helpers\AdminHelper;
use Modules\Appointments\Http\Requests\AppointmentRequest;
use Modules\Appointments\Repositories\AppointmentRepository;

/**
 * Class AppointmentsController
 *
 * Appointment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Announcement
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AppointmentsController extends Controller
{
    protected $appointmentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param AppointmentRepository $appointment [object]
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointment)
    {
        $this->appointmentRepo = $appointment;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(71, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->appointmentRepo->findAll($request);
    }

    /**
     * Show the specified resource.
     *
     * @param Int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(71, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->appointmentRepo->findById($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get appointments]
     *
     * @return Response
     */
    public function getAllAppointments(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(71, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->appointmentRepo->getAllAppointments($request);
    }

    /**
     * Get all slot for given provider and start date.
     *
     * @return Response
     */
    public function getAllBookedTimeSlot(Request $request, $id, $date)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(71, 'view')) {
            return response()->json("Access denied", 403);
        }
        
        return $this->appointmentRepo->getAllBookedTimeSlot($id, $date);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AppointmentRequest $request [Params for create appointment]
     *
     * @return Response
     */
    public function store(AppointmentRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(71, 'created')) {
            return response()->json("Access denied", 403);
        }

        $appointment = $this->appointmentRepo->create($request);
        if ($appointment) {
            if(is_array($appointment) && $appointment['is_booked']){
               return response()->json(["error" => "This timeslot is not available."], 422); 
            }
            return response()->json('success');
        } else {
            return response()->json('error', 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AppointmentRequest $request [Params for update appointment]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function update(AppointmentRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(71, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $appointment = $this->appointmentRepo->update($request, $id);
        if ($appointment) {
            if(is_array($appointment) && $appointment['is_booked']){
               return response()->json(["error" => "This timeslot is not available."], 422); 
            }
            return response()->json('success');
        } else {
            return response()->json('error', 400);
        }
    }

    /**
     * Change appointment status.
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

        if ($this->appointmentRepo->changeStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for destroy appointment]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(71, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->appointmentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 400);
        }
    }
}
