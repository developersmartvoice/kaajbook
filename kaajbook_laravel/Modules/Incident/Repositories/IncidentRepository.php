<?php

namespace Modules\Incident\Repositories;

use Auth;
use Carbon\Carbon;
use DB;
use Modules\CustomField\Models\CustomField;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Repositories\HelperRepository;
use Modules\Incident\Models\Incident;
use Modules\Incident\Models\IncidentHistory;
use Modules\Incident\Models\IncidentUser;
use Modules\Projects\Models\Project;
use Modules\Slack\Repositories\SlackRepository;
use Modules\Team\Models\Team;
use Modules\Timesheet\Models\Timesheet;
use Modules\UserActivity\Models\UserActivity;
use Modules\User\Models\User\User;
use Modules\Setting\Models\Setting;

/**
 * Class IncidentRepository
 *
 * Incident create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Incident
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentRepository
{
    protected $helperRepo;
    protected $emailsHelper;
    protected $slackRepo;

    /**
     * Instantiate a new reposiroty instance.
     *
     * @param HelperRepository $helperRepo   [Object]
     * @param EmailsHelper     $emailsHelper [Object]
     *
     * @return void
     */
    public function __construct(
        HelperRepository $helperRepo,
        EmailsHelper $emailsHelper,
        SlackRepository $slackRepo
    ) {
        $this->helperRepo = $helperRepo;
        $this->emailsHelper = $emailsHelper;
        $this->slackRepo = $slackRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        $user = Auth::user();
        return $user->incidents()
            ->with(['users' => function ($query) {
                $query->select('id', 'firstname', 'lastname');
            }])
            ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get incidents]
     *
     * @return Object
     */
    public function getAllIncidents($request)
    {
        $incident_table = config('core.acl.incidents_table');
        $user_table = config('core.acl.users_table');
        $user = Auth::user();

        $columns = array(
            0 => $incident_table.'.generated_id',
            1 => $incident_table.'.incident_name',
            2 => $incident_table.'.start_date',
            3 => $incident_table.'.end_date',
            4 => $incident_table.'.actual_hours',
            5 => 'incident_assigned.firstname',
            6 => $incident_table.'.priority',
            7 => $incident_table.'.status'
        );

        if ($request->get('isUserProfile') && $request->has('user_id')) {
            $incident = Incident::where($incident_table.'.assign_to', $request->get('user_id'));
            $statusCount = [];

            $columns = array(
                0 => $incident_table.'.generated_id',
                1 => $incident_table.'.incident_name',
                2 => $incident_table.'.start_date',
                3 => $incident_table.'.end_date',
                4 => $incident_table.'.incident_type',
                5 => $incident_table.'.priority',
                6 => $incident_table.'.status'
            );

        } elseif($request->has('filter') && $request->get('filter') === "selected") {
            $incident = Incident::where(function ($query) use ($incident_table, $user) {
                $query->where($incident_table.'.assign_to', $user->id)
                    ->orWhere($incident_table.'.create_user_id', $user->id);
            });
            $statusCount = $this->_getAllIncidentCount(true);
        } else {
            $incident =  $user->incidents();
            $statusCount = $this->_getAllIncidentCount();
        }

        $status = $request->get('status');
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $incident =  $incident->select(
            $incident_table.'.*',
            'incident_assigned.firstname as assigned_firstname',
            'incident_assigned.lastname as assigned_lastname',
            'incident_assigned.avatar as assigned_avatar'
        )
        ->leftjoin($user_table.' as incident_assigned', 'incident_assigned.id', '=', $incident_table.'.assign_to');

        if ($status) {
            if ($status == 1) {
                $incident->whereIn($incident_table.'.status', [1, 6]);
            } elseif ($status == 4) {
                $incident->whereIn($incident_table.'.status', [4, 5, 7]);
            } elseif ($status == 8) {
                // Overdue
                $incident->whereIn($incident_table.'.status', [1, 2, 3, 6])
                    ->whereDate($incident_table.'.end_date', '<', Carbon::now());
            } else{
                $incident->whereIn($incident_table.'.status', [$status]);
            }
        }

        $totalData = $incident->count();
        $totalFiltered = $totalData;

        // --
        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $incident = $incident->where(
                function ($query) use ($search, $incident_table) {
                    $query->where($incident_table.'.incident_name', 'LIKE', "%{$search}%")
                        ->orWhere($incident_table.'.generated_id', 'LIKE', "%{$search}%")
                        ->orWhere($incident_table.'.start_date', 'LIKE', "%{$search}%")
                        ->orWhere($incident_table.'.end_date', 'LIKE', "%{$search}%")
                        ->orWhere($incident_table.'.actual_hours', 'LIKE', "%{$search}%")
                        ->orWhere($incident_table.'.priority', 'LIKE', "%{$search}%")
                        ->orWhere(DB::raw('concat(incident_assigned.firstname," ",incident_assigned.lastname)'), 'LIKE', "%{$search}%")
                        ->orWhere($incident_table.'.status', 'LIKE', "%{$search}%")
                        ->orWhere($incident_table.'.project_version', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $incident->count();
        }

        $data = $incident->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        return array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "statusCount"     => $statusCount,
            "data"            => $data
        );
    }

    /**
     * Get all incident count.
     *
     * @param Boolean  $isMy [For all incident or user incident]
     *
     * @return Array
     */
    private function _getAllIncidentCount($isMy = false)
    {
        $user = Auth::user();
        $result['all'] =  $this->_getStatusWiseCount([1, 2, 3, 4, 5, 6, 7], $user, $isMy);
        if ($result['all'] > 0) {
            $result['open'] = $this->_getStatusWiseCount([1], $user, $isMy);
            $result['assigned'] = $this->_getStatusWiseCount([2], $user, $isMy);
            $result['in_progress'] = $this->_getStatusWiseCount([3], $user, $isMy);
            $result['solved'] = $this->_getStatusWiseCount([4], $user, $isMy);
            $result['deferred'] = $this->_getStatusWiseCount([5], $user, $isMy);
            $result['re_open'] = $this->_getStatusWiseCount([6], $user, $isMy);
            $result['closed'] = $this->_getStatusWiseCount([7], $user, $isMy);
            $result['overdue'] = $this->_getOverdueCount($user, $isMy);
        }
        return $result;
    }

    /**
     * Incident status wise counting.
     *
     * @param Int    $status [Status id]
     * @param object $user   [User object]
     * @param Boolean $isMy  [For all incident or user incident]
     *
     * @return Count
     */
    private function _getStatusWiseCount($status, $user, $isMy)
    {
        if ($isMy) {
            return Incident::where(function ($query) use ($user) {
                $query->where('assign_to', $user->id)
                    ->orWhere('create_user_id', $user->id);
            })
            ->whereIn('status', $status)
            ->count();
        } else {
            return $user->incidents()
                ->whereIn('status', $status)
                ->count();
        }
    }

    /**
     * Incident overdue counting.
     *
     * @param object $user   [User object]
     * @param Boolean $isMy  [For all incident or user incident]
     *
     * @return Count
     */
    private function _getOverdueCount($user, $isMy)
    {
        if ($isMy) {
            $overdueIncident = Incident::where(function ($query) use ($user) {
                $query->where('assign_to', $user->id)
                    ->orWhere('create_user_id', $user->id);
            });
        } else {
            $overdueIncident = $user->incidents();
        }

        return $overdueIncident->whereIn('status', [1, 2, 3, 6])
            ->whereDate('end_date', '<', Carbon::now())
            ->count();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get incident by project]
     *
     * @return Array
     */
    public function getIncidentForKanban($request)
    {
        $user = Auth::user();
        $user_table = config('core.acl.users_table');
        $incident_table = config('core.acl.incidents_table');

        if($request->has('filter') && $request->get('filter') === "my") {
            $incident = Incident::where(function ($query) use ($incident_table, $user) {
                $query->where($incident_table.'.assign_to', $user->id)
                    ->orWhere($incident_table.'.create_user_id', $user->id);
            });
        } else {
            $incident =  $user->incidents();
        }

        $incident = $incident->with([
            'assignUser' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname',
                    'avatar'
                );
            }]);

        if ($request->has('project_id')) {
            $incident->where($incident_table.'.project_id', $request->input('project_id'));
        }

        return $incident->orderBy($incident_table.'.status', 'asc')
            ->orderBy($incident_table.'.orders', 'asc')
            ->get();
    }

    /**
     * Display the specified resource in storage.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findByIdWithHistory($id)
    {
        $incident = Incident::with([
            'project' => function ($query) {
                $query->select('id', 'project_name', 'project_version', 'start_date', 'end_date');
            },
            'comments.user' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            } ,
            'createdUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'attachments',
            'history'=> function ($query) {
                $query->orderBy('id','desc');
            },
            'users' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            }
        ])
        ->where('id', $id)
        ->first();
        
        if ($incident) {
            $incident['custom_fields'] = CustomField::getViewFields(4)->get();
            $incident['activities'] = UserActivity::getActivities(['Incident','IncidentComment','IncidentAttachment'],$id)->get();
            return $incident;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create incidents]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $project = Project::find($input['project_id']);
        $incident = new Incident;
        $user = Auth::user();
        $userIds = [];
        $input['generated_id'] = $this->getGeneratedId();
        $input['create_user_id'] = $user->id;
        $input['orders'] = Incident::count();
        if ($incident->fill($input)->save()) {
            if ($input['assigned_group_id']) {
                $teamData = Team::with([
                    'members' => function ($query) {
                        $query->select(config('core.acl.users_table').'.id');
                    }
                ])
                ->where('id', $input['assigned_group_id'])
                ->first();
                foreach ($teamData->members as $value) {
                    $userIds[] = $value->id;
                }
            }

            $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();
            $userIds = array_merge($userIds, $super_admin_ids);
            array_push($userIds, $user->id); // login user
            if($project && $project->client_id){
                array_push($userIds, $project->client_id); // client
            }
            $uniqueUserId = array_unique($userIds);

            $incident->users()->sync($uniqueUserId);
            // --
            // Save custom field.
            if (isset($input['custom_fields'])) {
                $this->helperRepo->saveCustomField(4,$incident->id,$input['custom_fields']);
            }
            // --
            // Create user activity.
            createUserActivity(
                Incident::MODULE_NAME,
                $incident->id,
                $request->method(),
                $input['incident_name'],
                $request->ip(),
                $incident->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Incident Created Successfully', $incident, 'Incident');

            // --
            // Create incident history.
            $history = $this->_createHistory(
                $input['incident_name'],
                $user->id,
                'create',
                $user->id,
                $input['status']
            );
            $incident->history()->save($history);
            // --
            // Send mail.
            if ($input['assign_to']) {

                // Assign user and Super admins.
                // $mailUserId = $super_admin_ids;
                // array_push($mailUserId, $input['assign_to']);
                // $mailUserId = array_unique($mailUserId);

                // All team member.
                if ($project) {
                    $uniqueUserId = array_diff( $uniqueUserId, [$project->client_id] ); // Unset client
                }

                $this->_sendMailEveryone(
                    $uniqueUserId,
                    $incident
                );

                $this->_sendIncidentStartReminder($incident);

                // Add Notification.
                createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Created.',  "#" . $incident->generated_id . ' - ' . $incident->incident_name);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update incidents]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $incident = Incident::find($id);
        $oldIncident = $incident->toArray();
        $project = Project::find($input['project_id']);
        $user = Auth::user();
        $userIds = [];
        if ($incident->fill($input)->save()) {
            if ($input['assigned_group_id']) {
                $teamData = Team::with([
                    'members' => function ($query) {
                        $query->select(config('core.acl.users_table').'.id');
                    }
                ])
                ->where('id', $input['assigned_group_id'])
                ->first();
                foreach ($teamData->members as $value) {
                    $userIds[] = $value->id;
                }
            }

            $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();
            $userIds = array_merge($userIds, $super_admin_ids);
            array_push($userIds, $user->id); // login user
            if($project && $project->client_id){
                array_push($userIds, $project->client_id); // client
            }
            $uniqueUserId = array_unique($userIds);
            // --
            // Sync user.
            $incident->users()->sync($uniqueUserId);
            // --
            // Save custom field
            if (isset($input['custom_fields'])) {
                $this->helperRepo->saveCustomField(4, $id, $input['custom_fields']);
            }
            // --
            // Create history.
            $history = $this->_createHistory(
                $input['incident_name'],
                $user->id,
                'edit',
                $incident->create_user_id,
                $incident->status
            );
            $incident->history()->save($history);
            // --
            // Create user activity.
            createUserActivity(
                Incident::MODULE_NAME,
                $id,
                $request->method(),
                $input['incident_name'],
                $request->ip(),
                $id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Incident Updated Successfully', $incident, 'Incident');

            // --
            // Send email.
            if ($input['assign_to']) {

                // Assign user and Super admins.
                // $mailUserId = $super_admin_ids;
                // array_push($mailUserId, $input['assign_to']);
                // $mailUserId = array_unique($mailUserId);

                // All team member.
                if ($project) {
                    $uniqueUserId = array_diff( $uniqueUserId, [$project->client_id] ); // Unset client
                } 
                
                $userData = User::select('id', 'email', DB::raw("CONCAT(firstname,' ',lastname) as name"))
                    ->whereIn('id', $uniqueUserId)
                    ->where('is_active', 1)
                    ->get();
                // --
                // Send email
                $this->emailsHelper->sendIncidentUpdateMails($userData, $incident);

                $this->_sendIncidentStartReminder($incident);

                // Add Notification.
                createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Updated.',  "#" . $incident->generated_id . ' - ' . $incident->incident_name);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Send incident start reminder.
     *
     * @param Object $incident [Incident object]
     *
     * @return void
     */
    private function _sendIncidentStartReminder($incident){
        if ($incident->start_date) {
            $setting = Setting::select('reminder_alert_before_days')->first();
            $beforeDays = explode(',', $setting->reminder_alert_before_days);

            foreach ($beforeDays as $days) {
                $reminderDate = Carbon::now()->addDays($days)->format('Y-m-d');
                if ($incident->start_date->format('Y-m-d') == $reminderDate) {
                    $this->emailsHelper->sendIncidentReminderEmails($incident);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete incidents]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $incident = Incident::findOrFail($id);
        if ($incident) {
            $incident->users()->detach();
            if ($incident->delete()) {
                Timesheet::where('module_id', 4)->where('module_related_id', $id)->delete();
                // --
                // Create user activity.
                createUserActivity(
                    Incident::MODULE_NAME,
                    $incident->id,
                    $request->method(),
                    $incident->incident_name,
                    $request->ip(),
                    $incident->id
                );

                // --
                // Post message to slack
                $this->slackRepo->postActivitiesMessage('Incident Deleted Successfully', $incident, 'Incident');

                // Add Notification.
                createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Deleted.',  "#" . $incident->generated_id . ' - ' . $incident->incident_name, $request->method());

                return true;
            }
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update incidents notes]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function incidentNotesUpdate($request, $id)
    {
        $input = $request->all();
        $incident = Incident::findOrFail($id);

        if ($incident->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                Incident::MODULE_NAME,
                $incident->id,
                $request->method(),
                $incident->incident_name,
                $request->ip(),
                $incident->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Incident Updated Successfully', $incident, 'Incident');

            return true;
        } else {
            return false;
        }
    }

    /**
     * Incident send mail.
     *
     * @param Array  $uniqueUserId  [User array]
     * @param Object $incident      [Incident]
     *
     * @return Boolean
     */
    private function _sendMailEveryone($uniqueUserId, $incident) {
        $userData = User::select('id','firstname', 'lastname','email')
            ->whereIn('id', $uniqueUserId)
            ->where('is_active', 1)
            ->get();

        if (!$userData->isEmpty()) {
            // --
            // Send email
            $this->emailsHelper->sendIncidentAssignMails(
                $userData,
                $incident
            );
            return true;
        }
        return false;
    }

    /**
     * Changed Incident status.
     *
     * @param Request $request [Request for change status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeStatus($request, $id)
    {
        $statusId = $request->get('status');
        $incident = Incident::findOrFail($id);
        $user = Auth::user();
        if ($incident) {
            $input['status'] = $statusId;
            $incident->fill($input)->save();
            // --
            // Create history.
            $history = $this->_createHistory(
                $incident->incident_name,
                $user->id,
                'status',
                $incident->create_user_id,
                $input['status']
            );
            $incident->history()->save($history);
            // --
            // Create user activity.
            createUserActivity(
                Incident::MODULE_NAME,
                $incident->id,
                $request->method(),
                $incident->incident_name,
                $request->ip(),
                $incident->id,
                true
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Incident Status Changed Successfully', $incident, 'Incident');

            // --
            // Send email
            $this->emailsHelper->sendIncidentStatusChangeMails($incident);

            // Add Notification.
            createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Status Changed.',  "#" . $incident->generated_id . ' - ' . $incident->incident_name);

            return true;
        }
        return false;
    }

    /**
     * Changed incident severity.
     *
     * @param Request $request [Request for change incident severity]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeIncidentSeverity($request, $id)
    {
        $input['priority'] = $request->get('priority');
        $incident = Incident::findOrFail($id);
        $user = Auth::user();
        if ($incident) {
            $incident->fill($input)->save();
            // --
            // Create history.
            $history = $this->_createHistory(
                $incident->incident_name,
                $user->id,
                'priority',
                $incident->create_user_id,
                $input['priority']
            );
            $incident->history()->save($history);

            // --
            // Create user activity.
            createUserActivity(
                Incident::MODULE_NAME,
                $incident->id,
                $request->method(),
                $incident->incident_name,
                $request->ip(),
                $incident->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Incident Severity Changed Successfully', $incident, 'Incident');

            // Add Notification.
            createNotification([$incident->assignUser], 'incidents', $incident->id, 'Incident Severity Updated.',  "#" . $incident->generated_id . ' - ' . $incident->incident_name);

            return true;
        }
        return false;
    }

    /**
     * Store incident history.
     *
     * @param String $incidentName [Incident name]
     * @param Int    $loginUserId  [Login user id]
     * @param String $method       [Request method]
     * @param Int    $createUserId [Created user id]
     * @param String $statusType   [Incident status type]
     *
     * @return Object
     */
    private function _createHistory(
        $incidentName,
        $loginUserId,
        $method,
        $createUserId,
        $statusType = 0
    ) {
        $incidentHistory = new IncidentHistory;
        $incidentHistory->created_by_id = $createUserId;
        if ($method == 'create') {
            $incidentHistory->description = "Incident information added : "
            . '<b>'.$incidentName.'</b>';
        } elseif ($method == 'edit') {
            $incidentHistory->description = "Incident information updated : "
            . '<b>'.$incidentName.'</b>';
            $incidentHistory->updated_by_id = $loginUserId;
        } elseif ($method == 'status') {
            $incidentHistory->description = "Incident status Changed : "
            . '<b>'.$incidentName.'</b>';
            $incidentHistory->updated_by_id = $loginUserId;
        } else {
            $incidentHistory->description = "Incident information deleted : "
            . '<b>'.$incidentName.'</b>';
            $incidentHistory->updated_by_id = $loginUserId;
        }

        if ($statusType == 7) {
            $incidentHistory->closed_by_id = $loginUserId;
        }
        if ($statusType == 4) {
            $incidentHistory->solved_by_id = $loginUserId;
        }
        return $incidentHistory;
    }

    /**
     * Retrive generated id.
     *
     * @return String
     */
    public function getGeneratedId()
    {
        return 'INC'.str_pad(nextAutoID(config('core.acl.incidents_table')), 4, "0", STR_PAD_LEFT);
    }

    /**
     * Get incident for report.
     *
     * @param Request $request [Request for get incident report]
     *
     * @return Response
     */
    public function getIncidentForReport($request)
    {
        $incident_table = config('core.acl.incidents_table');
        $project_table = config('core.acl.projects_table');
        $user_table = config('core.acl.users_table');
        $user = Auth::user();

        $columns = array(
            0 => $incident_table.'.id',
            1 => $incident_table.'.incident_name',
            2 => $incident_table.'.start_date',
            3 => $incident_table.'.end_date',
            4 => $incident_table.'.actual_hours',
            5 => DB::raw("CONCAT(incident_created.firstname,' ',incident_created.lastname)"),
            6 =>  DB::raw("CONCAT(incident_assigned.firstname,' ',incident_assigned.lastname)"),
            7 => $incident_table.'.incident_type',
            8 => $incident_table.'.priority',
            9 => $incident_table.'.status',
            10 => $project_table.'.project_name',
            11 => $project_table.'.project_version'
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $columns_search = $request->input('columns');
        
        $incident = $user->incidents()->leftjoin($project_table, $project_table.'.id', '=', $incident_table.'.project_id')
            ->join($user_table.' as incident_created', 'incident_created.id', '=', $incident_table.'.create_user_id')
            ->leftjoin($user_table.' as incident_assigned', 'incident_assigned.id', '=', $incident_table.'.assign_to')
            ->select(
                $incident_table.'.*',
                $project_table.'.project_name',
                'incident_created.firstname as created_firstname',
                'incident_created.lastname as created_lastname',
                'incident_created.avatar as created_avatar',
                'incident_assigned.firstname as assigned_firstname',
                'incident_assigned.lastname as assigned_lastname',
                'incident_assigned.avatar as assigned_avatar'
            );
            
        $matchThese = [];
        foreach ((array)$columns_search as $key => $value) {
            if (!empty($value['search']['value'])) {
                array_push(
                    $matchThese,
                    [$columns[$key],'LIKE',"%{$value['search']['value']}%"]
                );
            }
        }

        $totalData = $incident->count();
        $totalFiltered = $totalData;

        if (!empty($matchThese)) {
            $incident = $incident->where($matchThese);
            $totalFiltered = $incident->count();
        }

        $data = $incident->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        return array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
    }

    /**
     * Get incidents for calendar.
     *
     * @param Request $request [Request for get incidents]
     *
     * @return Response
     */
    public function getIncidentForCalendar($request)
    {
        $user = Auth::user();
        return Incident::with(
            ['assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname','avatar');
            }]
        )
        ->where(function ($query) use ($user){
            $query->where('assign_to', $user->id)
                ->orWhere('create_user_id', $user->id);
        })
        ->whereNotIn('status', [4, 5, 7])
        ->orderBy('created_at', 'DESC')
        ->get();
    }

    /**
     * Check user permission.
     *
     * @param Int $incident_id [Incident Id]
     *
     * @return Boolean
     */
    public function checkPermission($incident_id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $incidentUser = Incident::where('id', $incident_id)
            ->where(
                function ($q) {
                    $q->where('assign_to', Auth::user()->id)
                        ->orWhere('create_user_id', Auth::user()->id);
                }
            )
            ->first();

        if ($incidentUser) {
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource
     *
     * @param Request $request [Request for update incident status and order]
     *
     * @return Boolean
     */
    public function updateKanban($request)
    {
        $order = 1;
        $input = $request->all();

        if (!empty($input)) {
            foreach ($input as $key => $value) {
                if (isset($value['list'])) {
                    foreach ($value['list'] as $Key1 => $Value1) {
                        $incident = Incident::findOrFail($Value1);
                        $incident->status = $value['status'];
                        $incident->orders = $order;
                        $incident->save();
                        $order++;
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
