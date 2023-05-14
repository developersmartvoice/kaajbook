<?php

namespace Modules\Appointments\Repositories;

use Auth;
use Carbon\Carbon;
use DB;
use Modules\Appointments\Models\Appointment;
use Modules\Appointments\Models\AppointmentRecurrence;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Provider\Models\Provider;
use Modules\User\Models\User\User;

/**
 * Class AppointmentRepository
 *
 * Appointment CRUD functionality.
 *
 * Appointment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  CRM
 * @package   Modules\Appointments
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AppointmentRepository
{
    protected $emailRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param EmailsHelper     $emailRepo  [Object]
     *
     * @return void
     */
    public function __construct(EmailsHelper $emailRepo)
    {
        $this->emailRepo = $emailRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return object
     */
    public function findAll($request)
    {
        $user = Auth::user();
        $status = $request->input("status");
        $selectd_date = $request->input("date");
        $month = null;
        $year = null;
        $statusCount = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
        ];

        if ($user->is_client) {
            $appointments = Appointment::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)->orWhere('client_id', $user->id);
            });
        } elseif (!$user->hasRole('admin') && !$user->is_super_admin) {
            $appointments = Appointment::where('user_id', $user->id);
        } else {
            $appointments = new Appointment;
        }

        if ($selectd_date != 'null') {
            $month = date("m", strtotime($selectd_date));
            $year = date("Y", strtotime($selectd_date));
            $appointments = $appointments->whereMonth('start_date_time', $month)->whereYear('start_date_time', $year);
        }

        if ($status != 0) {
            $appointments = $appointments->where("status", $status);
        }

        $appointments = $appointments->get();

        foreach ($appointments as $appointment) {
            if ($appointment->recurrence) {
                $appointmentRecurrences = AppointmentRecurrence::where('appointment_id', $appointment->id);
                if ($month && $year) {
                    $appointmentRecurrences = $appointmentRecurrences->whereMonth('start_date_time', $month)->whereYear('start_date_time', $year);
                }
                if ($status != 0) {
                    $appointmentRecurrences = $appointmentRecurrences->where("status", $status);
                }
                $appointmentRecurrences = $appointmentRecurrences->get();
                foreach ($appointmentRecurrences as $appointmentRecurrence) {
                    $_appointment = Appointment::where('id', $appointmentRecurrence->appointment_id)->first();
                    $_appointment->id = $appointmentRecurrence->id;
                    $_appointment->appointment_id = $appointmentRecurrence->appointment_id;
                    $_appointment->start_date_time = $appointmentRecurrence->start_date_time;
                    $_appointment->end_date_time = $appointmentRecurrence->end_date_time;
                    $_appointment->status = $appointmentRecurrence->status;
                    $appointments[] = $_appointment;
                    $statusCount[$appointmentRecurrence->status] = $statusCount[$appointmentRecurrence->status] + 1;
                }
            }
        }

        return array(
            "statusCount" => $statusCount,
            "data" => $appointments,
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get appointments]
     *
     * @return Object
     */
    public function getAllAppointments($request)
    {
        $user = Auth::user();
        $appointment_table = config('core.acl.appointment_table');
        $providers_table = config('core.acl.providers_table');

        $columns = array(
            0 => $appointment_table . '.id',
            1 => $appointment_table . '.title',
            2 => $providers_table . '.firstname',
            3 => $appointment_table . '.start_date_time',
            4 => $appointment_table . '.end_date_time',
            5 => $appointment_table . '.location',
            6 => $appointment_table . '.status',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $appointment = Appointment::select(
            $appointment_table . '.*',
            $providers_table . '.firstname as provider_firstname',
            $providers_table . '.lastname as provider_lastname'
        )
            ->join($providers_table, $providers_table . '.id', '=', $appointment_table . '.provider_id');

        if ($user->is_client) {
            $appointment->where(function ($query) use ($user, $appointment_table) {
                $query->where($appointment_table . '.user_id', $user->id)
                    ->orWhere($appointment_table . '.client_id', $user->id);
            });
        } elseif (!$user->hasRole('admin') && !$user->is_super_admin) {
            $appointment->where($appointment_table . '.user_id', $user->id);
        }

        $status = $request->get('status');
        if ($status) {
            $appointment->where($appointment_table . '.status', $status);
        }

        $totalData = $appointment->count();
        $totalFiltered = $totalData;

        // --
        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $appointment = $appointment->where(
                function ($query) use ($search, $appointment_table) {
                    $query->where($appointment_table . '.generated_id', 'LIKE', "%{$search}%")
                        ->orWhere($appointment_table . '.title', 'LIKE', "%{$search}%")
                        ->orWhere($appointment_table . '.start_date_time', 'LIKE', "%{$search}%")
                        ->orWhere($appointment_table . '.end_date_time', 'LIKE', "%{$search}%")
                        ->orWhere($appointment_table . '.location', 'LIKE', "%{$search}%")
                        ->orWhere($appointment_table . '.status', 'LIKE', "%{$search}%")
                        ->orWhere(
                            DB::raw('concat($providers_table.firstname," ",$providers_table.lastname)'),
                            'LIKE',
                            "%{$search}%"
                        );
                }
            );

            $totalFiltered = $appointment->count();
        }

        $data = $appointment->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $statusCount = $this->_getAppointmentCountForList();

        return array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "statusCount" => $statusCount,
            "data" => $data,
        );
    }

    /**
     * Get all appointment status wise count for list.
     *
     * @return json
     */
    private function _getAppointmentCountForList()
    {
        $user = Auth::user();
        $appointment = Appointment::select('status', DB::raw('count(*) as total'));

        if ($user->is_client) {
            $appointment->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)->orWhere('client_id', $user->id);
            });
        } elseif (!$user->hasRole('admin') && !$user->is_super_admin) {
            $appointment->where('user_id', $user->id);
        }

        $appointment = $appointment->groupBy('status')->get();

        for ($i = 0; $i < 5; $i++) {
            $result[$i] = 0;
        }
        foreach ($appointment as $key => $value) {
            $result[$value->status] = $value->total;
            $result[0] = $result[0] + $value->total;
        }

        return $result;
    }

    /**
     * Display the specified resource in storage.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return Appointment::with([
            'clients' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'requesters' => function ($query) {
                $query->select('id', 'firstname', 'lastname');
            },
            'providers' => function ($query) {
                $query->select('id', 'firstname', 'lastname');
            },
            'recurrences',
        ])->findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Params for create appointment]
     *
     * @return boolean
     */
    public function create($request)
    {
        $user = Auth::user();
        $input = $request->all();
        $selectd_date = date("Y-m-d", strtotime($input["start_date_time"]));
        $booked_appointments = Appointment::where("provider_id", $input["provider_id"])
            ->whereDate('start_date_time', '=', $selectd_date)
            ->whereNotIn('status', [4]) // 4=Canceled
            ->get();

        $result = $this->_validate_date($input, $booked_appointments);
        if ($result['is_booked']) {
            return $result;
        }
        $appointment = new Appointment;
        $input['user_id'] = $user->id;
        $input['generated_id'] = $this->_getGeneratedId();
        $appointment->fill($input);
        if ($appointment->save()) {

            if ($appointment->recurrence) {
                // Create appointment recurrence.
                $this->_appointmentRecurrence($appointment);
            }

            // --
            // Add activities
            createUserActivity(
                Appointment::MODULE_NAME,
                $appointment->id,
                $request->method(),
                $appointment->title,
                $request->ip()
            );

            // --
            $appointment = Appointment::findOrFail($appointment->id);
            $mailUser = [];
            array_push($mailUser, User::select('id', 'firstname', 'lastname', 'email')->find($user->id));
            array_push($mailUser, Provider::select('id', 'firstname', 'lastname', 'email')->find($input['provider_id']));
            if ($appointment->client_id && ($appointment->client_id != $appointment->user_id)) {
                array_push($mailUser, User::select('id', 'firstname', 'lastname', 'email')->find($appointment->client_id));
            }
            $this->emailRepo->sendAppointmentEmail($mailUser, $appointment);

            // Add Notification.
            createNotification([$appointment->clients], 'appointments', $appointment->id, 'Appointment Created.', "#" . $appointment->generated_id . ' - ' . $appointment->title);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Params for update appointment]
     * @param int     $id      [Row id]
     *
     * @return object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $selectd_date = date("Y-m-d", strtotime($input["start_date_time"]));
        $booked_appointments = Appointment::where("provider_id", $input["provider_id"])
            ->whereDate('start_date_time', '=', $selectd_date)
            ->whereNotIn('status', [4])
            ->whereNotIn('id', [$id])
            ->get();

        $result = $this->_validate_date($input, $booked_appointments);
        if ($result['is_booked']) {
            return $result;
        }

        $appointment = Appointment::find($id);
        $appointment->fill($input);
        if ($appointment->save()) {

            if ($appointment->recurrence) {
                // Remove future appointment recurrence on update.
                AppointmentRecurrence::where("appointment_id", $appointment->id)
                    ->where(DB::raw("(DATE_FORMAT(start_date_time,'%Y-%m-%d %H:%i'))"), ">", Carbon::now()->format('Y-m-d H:i'))
                    ->delete();

                // Create appointment recurrence.
                $this->_appointmentRecurrence($appointment);
            } else {
                AppointmentRecurrence::where("appointment_id", $appointment->id)->delete();
            }

            // --
            // Add activities
            createUserActivity(
                Appointment::MODULE_NAME,
                $appointment->id,
                $request->method(),
                $appointment->title,
                $request->ip()
            );

            // --
            $mailUser = [];
            array_push($mailUser, User::select('id', 'firstname', 'lastname', 'email')->find($appointment->user_id));
            array_push($mailUser, Provider::select('id', 'firstname', 'lastname', 'email')->find($input['provider_id']));
            if ($appointment->client_id && ($appointment->client_id != $appointment->user_id)) {
                array_push($mailUser, User::select('id', 'firstname', 'lastname', 'email')->find($appointment->client_id));
            }
            $this->emailRepo->sendAppointmentEmail($mailUser, $appointment);

            // Add Notification.
            createNotification([$appointment->clients], 'appointments', $appointment->id, 'Appointment Updated.', "#" . $appointment->generated_id . ' - ' . $appointment->title);
           
            return true;
        } else {
            return false;
        }
    }

    private function _validate_date($input, $booked_appointments)
    {
        foreach ($booked_appointments as $key => $value) {

            $selected_start_time = date("H:i", strtotime($input["start_date_time"]));
            $selected_end_time = date("H:i", strtotime($input["end_date_time"]));
            $chkStartTime = strtotime($selected_start_time);
            $chkEndTime = strtotime($selected_end_time);

            $start_time = date("H:i", strtotime($value->start_date_time));
            $end_time = date("H:i", strtotime($value->end_date_time));
            $startTime = strtotime($start_time);
            $endTime = strtotime($end_time);

            if ($chkStartTime > $startTime && $chkEndTime < $endTime) { #-> Check time is in between start and end time
            return ["is_booked" => true, "message" => "Time is in between start and end time"];
            } elseif (($chkStartTime > $startTime && $chkStartTime < $endTime) || ($chkEndTime > $startTime && $chkEndTime < $endTime)) { #-> Check start or end time is in between start and end time
            return ["is_booked" => true, "message" => "Check start or end Time is in between start and end time"];
            } elseif ($chkStartTime == $startTime || $chkEndTime == $endTime) { #-> Check start or end time is at the border of start and end time
            return ["is_booked" => true, "message" => "Check start or end Time is at the border of start and end time"];
            } elseif ($startTime > $chkStartTime && $endTime < $chkEndTime) { #-> start and end time is in between  the check start and end time.
            return ["is_booked" => true, "message" => "Start and end Time is overlapping  Check start and end time"];
            }
        }
        return ["is_booked" => false];
    }

    /**
     * Create appointment recurrence.
     *
     * @param Object $appointment [Appointment object]
     *
     * @return Boolean
     */
    private function _appointmentRecurrence($appointment)
    {
        if ($appointment->recurrence_pattern == 'daily') {
            for ($i = 1; $i <= $appointment->recurrence_occurrences; $i++) {
                $appointmentRecurrence = new AppointmentRecurrence();
                $input['appointment_id'] = $appointment->id;
                $input['start_date_time'] = $appointment->start_date_time->addDays($i);
                $input['end_date_time'] = $appointment->end_date_time->addDays($i);
                $appointmentRecurrence->fill($input)->save();
            }
        } elseif ($appointment->recurrence_pattern == 'weekly') {
            for ($i = 1; $i <= $appointment->recurrence_occurrences; $i++) {
                $appointmentRecurrence = new AppointmentRecurrence();
                $input['appointment_id'] = $appointment->id;
                $input['start_date_time'] = $appointment->start_date_time->addWeeks($i);
                $input['end_date_time'] = $appointment->end_date_time->addWeeks($i);
                $appointmentRecurrence->fill($input)->save();
            }
        } elseif ($appointment->recurrence_pattern == 'monthly') {
            for ($i = 1; $i <= $appointment->recurrence_occurrences; $i++) {
                $appointmentRecurrence = new AppointmentRecurrence();
                $input['appointment_id'] = $appointment->id;
                $input['start_date_time'] = $appointment->start_date_time->addMonths($i);
                $input['end_date_time'] = $appointment->end_date_time->addMonths($i);
                $appointmentRecurrence->fill($input)->save();
            }
        } elseif ($appointment->recurrence_pattern == 'quarterly') {
            for ($i = 1; $i <= $appointment->recurrence_occurrences; $i++) {
                $appointmentRecurrence = new AppointmentRecurrence();
                $input['appointment_id'] = $appointment->id;
                $input['start_date_time'] = $appointment->start_date_time->addMonths($i * 3);
                $input['end_date_time'] = $appointment->end_date_time->addMonths($i * 3);
                $appointmentRecurrence->fill($input)->save();
            }
        } elseif ($appointment->recurrence_pattern == 'semi_annually') {
            for ($i = 1; $i <= $appointment->recurrence_occurrences; $i++) {
                $appointmentRecurrence = new AppointmentRecurrence();
                $input['appointment_id'] = $appointment->id;
                $input['start_date_time'] = $appointment->start_date_time->addMonths($i * 6);
                $input['end_date_time'] = $appointment->end_date_time->addMonths($i * 6);
                $appointmentRecurrence->fill($input)->save();
            }
        } elseif ($appointment->recurrence_pattern == 'annually') {
            for ($i = 1; $i <= $appointment->recurrence_occurrences; $i++) {
                $appointmentRecurrence = new AppointmentRecurrence();
                $input['appointment_id'] = $appointment->id;
                $input['start_date_time'] = $appointment->start_date_time->addYears($i);
                $input['end_date_time'] = $appointment->end_date_time->addYears($i);
                $appointmentRecurrence->fill($input)->save();
            }
        }
        return true;
    }

    /**
     * Changed appointment status.
     *
     * @param Request $request [Request for change appointment status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeStatus($request, $id)
    {
        $input['status'] = $request->get('status');
        $appointment = Appointment::findOrFail($id);
        if ($appointment) {
            $appointment->fill($input)->save();
            // --
            // Add activity
            createUserActivity(
                Appointment::MODULE_NAME,
                $appointment->id,
                $request->method(),
                $appointment->title,
                $request->ip(),
                $appointment->id,
                true
            );

            // --
            $mailUser = [];
            array_push($mailUser, User::select('id', 'firstname', 'lastname', 'email')->find($appointment->user_id));
            array_push($mailUser, Provider::select('id', 'firstname', 'lastname', 'email')->find($appointment->provider_id));
            if ($appointment->client_id && ($appointment->client_id != $appointment->user_id)) {
                array_push($mailUser, User::select('id', 'firstname', 'lastname', 'email')->find($appointment->client_id));
            }
            $this->emailRepo->sendAppointmentEmail($mailUser, $appointment);

            // Add Notification.
            createNotification([$appointment->clients], 'appointments', $appointment->id, 'Appointment Status Changed.', "#" . $appointment->generated_id . ' - ' . $appointment->title);
            return true;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for delete appointment]
     * @param int     $id      [Row id]
     *
     * @return boolean
     */
    public function delete($request, $id)
    {
        $appointment = Appointment::find($id);

        if ($appointment) {
            AppointmentRecurrence::where("appointment_id", $appointment->id)->delete();
            $appointment->delete();
            
            // --
            // Add activities
            createUserActivity(
                Appointment::MODULE_NAME,
                $appointment->id,
                $request->method(),
                $appointment->title,
                $request->ip()
            );
            
            // Add Notification.
            createNotification([$appointment->clients], 'appointments', $appointment->id, 'Appointment Deleted.', "#" . $appointment->generated_id . ' - ' . $appointment->title, $request->method());
            
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all slot for given provider and start date.
     *
     * @return Response
     */
    public function getAllBookedTimeSlot($id, $date)
    {
        $selectd_date = date("Y-m-d", strtotime($date));
        return Appointment::where("provider_id", $id)
            ->whereDate('start_date_time', '=', $selectd_date)
            ->get();
    }

    /**
     * Retrive generated id.
     *
     * @return String
     */
    private function _getGeneratedId()
    {
        return 'A' . str_pad(nextAutoID(config('core.acl.appointment_table')), 5, "0", STR_PAD_LEFT);
    }
}
