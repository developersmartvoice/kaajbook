<?php

namespace Modules\Meeting\Repositories;

use Auth;
use Illuminate\Support\Facades\DB;
use Modules\Helper\Helpers\CommonHelper;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Meeting\Models\Meeting;
use Modules\Timesheet\Models\Timesheet;

/**
 * Class MeetingController
 *
 * Meeting create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Meeting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class MeetingRepository
{
    protected $emailsHelper;

    /**
     * Instantiate a new reposiroty instance.
     *
     * @param EmailsHelper $emailsHelper [Object]
     * @param CommonHelper $commonHelper [Object]
     *
     * @return void
     */
    public function __construct(EmailsHelper $emailsHelper, CommonHelper $commonHelper)
    {
        $this->emailsHelper = $emailsHelper;
        $this->commonHelper = $commonHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Meeting::with('user', 'members')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get meeting]
     *
     * @return Array
     */
    public function getAllMeetings($request)
    {
        $user = Auth::user();
        $users_table = config('core.acl.users_table');
        $meetings_table = config('core.acl.meetings');

        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'firstname',
            4 => 'start_date',
            5 => 'end_date',
            6 => 'hour_time',
            7 => 'status'
        );

        $meetings = $user->meetings();

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $meetings = $meetings->with([
            'members' => function ($query) {
                $query->select(
                    config('core.acl.users_table').'.id as member_id',
                    config('core.acl.users_table').'.firstname',
                    config('core.acl.users_table').'.lastname',
                    config('core.acl.users_table').'.avatar',
                    config('core.acl.users_table').'.is_client'
                );
            }
        ])
        ->join($users_table.' as user', 'user.id', '=', $meetings_table.'.organizer_id')
        ->select(
            config('core.acl.meetings').'.*',
            'user.firstname',
            'user.lastname',
            'user.avatar'
        );

        if (isset($input['statusId']) && $input['statusId']) {
            $meetings->where($meetings_table.'.status', $input['statusId']);
        }

        $totalData = $meetings->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            
            $meetings = $meetings->where('generated_id', 'LIKE', "%{$search}%")
                ->orWhere('title', 'LIKE', "%{$search}%")
                ->orWhere('firstname', 'LIKE', "%{$search}%")
                ->orWhere('lastname', 'LIKE', "%{$search}%")
                ->orWhere('start_date', 'LIKE', "%{$search}%")
                ->orWhere('end_date', 'LIKE', "%{$search}%")
                ->orWhere('hour_time', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%");

            $totalFiltered = $meetings->count();
        }

        $meetings = $meetings->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $statusCount = $this->_getMeetingCount();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "statusCount"     => $statusCount,
            "data"            => $meetings
        );

        return $json_data;
    }

    /**
     * Get all meeting status wise count.
     *
     * @return json
     */
    private function _getMeetingCount()
    {
        $user = Auth::user();
        $result['all'] = $user->meetings()->whereIn('status', [1, 2, 3, 4])->count();
        $result['open'] = $user->meetings()->where('status', 1)->count();
        $result['in_progress'] = $user->meetings()->where('status', 2)->count();
        $result['cancel'] = $user->meetings()->where('status', 3)->count();
        $result['completed'] = $user->meetings()->where('status', 4)->count();
        
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
        return Meeting::with([
            'user', 
            'members',
            'client' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'project'
        ])->where('id', $id)->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create meeting]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $logginUser = Auth::user();
        $meeting = new Meeting;
        $input['generated_id'] = $this->_getGeneratedId();
        $input['organizer_id'] = $logginUser->id;
        $input['hour_time'] = $this->commonHelper->getHourTimeDiff($input['start_date'], $input['end_date']);
        $meeting->fill($input);

        if ($meeting->save()) {
            if ($input['client_id']) {
                array_push($input['members'], $input['client_id']);
            }
            array_push($input['members'], $logginUser->id);
            $input['members'] = array_unique($input['members']);
            $meeting->members()->sync($input['members']);

            // --
            // Send mail
            $this->emailsHelper->sendMeetingEmails($input['members'], $meeting);
            // --
            // Add Activity
            createUserActivity(
                Meeting::MODULE_NAME,
                $meeting->id,
                $request->method(),
                $meeting->title,
                $request->ip()
            );

            // Add Notification.
            createNotification($meeting->members, 'meetings', $meeting->id, 'Meeting Created.', "#" . $meeting->generated_id . ' - ' . $meeting->title);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update meeting]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $logginUser = Auth::user();
        $input = $request->all();
        $meeting = Meeting::find($id);
        $input['hour_time'] = $this->commonHelper->getHourTimeDiff($input['start_date'], $input['end_date']);
        $meeting->fill($input);

        if ($meeting->save()) {
            if ($input['client_id']) {
                array_push($input['members'], $input['client_id']);
            }
            array_push($input['members'], $logginUser->id);
            $input['members'] = array_unique($input['members']);
            $meeting->members()->sync($input['members']);
            // --
            // Send mail
            if ($input['status'] == 3) {
                $this->emailsHelper->sendMeetingCancelEmails($input['members'],$meeting);
            }else{
                $this->emailsHelper->sendMeetingEmails($input['members'],$meeting);
            }
            // --
            // Add Activity
            createUserActivity(
                Meeting::MODULE_NAME,
                $meeting->id,
                $request->method(),
                $meeting->title,
                $request->ip()
            );

            // Add Notification.
            createNotification($meeting->members, 'meetings', $meeting->id, 'Meeting Updated.', "#" . $meeting->generated_id . ' - ' . $meeting->title);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Change meeting status.
     *
     * @param Request $request [Request for change status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeStatus($request, $id)
    {
        $users_table = config('core.acl.users_table');
        $statusId = $request->get('status');
        $meeting = Meeting::with([
            'members' => function ($query) use ($users_table) {
                $query->select($users_table.'.id');
            }
        ])->findOrFail($id);
        if ($meeting) {
            $input['status'] = $statusId;

            if ($meeting->fill($input)->save()) {
                if ($statusId == 3) {
                    // --
                    // Send cancel meeting email.
                    $users = $meeting->members->toArray();
                    $ids = array_column($users, 'id');
                    $this->emailsHelper->sendMeetingCancelEmails($ids,$meeting);
                }
                // --
                // Add activities
                createUserActivity(
                    Meeting::MODULE_NAME,
                    $meeting->id,
                    $request->method(),
                    $meeting->title,
                    $request->ip(),
                    $meeting->id,
                    true
                );
            }
            
            // Add Notification.
            createNotification($meeting->members, 'meetings', $meeting->id, 'Meeting Status Changed.', "#" . $meeting->generated_id . ' - ' . $meeting->title);

            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete meeting]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $meeting = Meeting::findOrFail($id);
        if (!empty($meeting)) {
            // Add Notification.
            createNotification($meeting->members, 'meetings', $meeting->id, 'Meeting Deleted.', "#" . $meeting->generated_id . ' - ' . $meeting->title, $request->method());

            $meeting->members()->sync(array());
            $meeting->delete();

            Timesheet::where('module_id', 5)->where('module_related_id', $id)->delete();

            // --
            // Add activity
            createUserActivity(
                Meeting::MODULE_NAME,
                $meeting->id,
                $request->method(),
                $meeting->title,
                $request->ip()
            );
            
            return true;
        }
        return false;
    }

    /**
     * Get meetings for calendar.
     *
     * @return Response
     */
    public function getMeetingForCalendar()
    {
        $user = Auth::user();
        return $user->meetings()
            ->whereNotIn('status', [3, 4])
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * Retrive generated id.
     *
     * @return String
     */
    private function _getGeneratedId()
    {
        return 'M'.str_pad(nextAutoID(config('core.acl.meetings')), 5, "0", STR_PAD_LEFT);
    }
}
