<?php
namespace Modules\Projects\Repositories;

use Auth;
use Carbon\Carbon;
use DB;
use Modules\CustomField\Models\CustomField;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Repositories\HelperRepository;
use Modules\ProjectPlannerSprint\Models\ProjectPlannerSprint;
use Modules\ProjectSprintTask\Models\ProjectSprintTask;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\ProjectUser;
use Modules\Setting\Models\Setting;
use Modules\Slack\Repositories\SlackRepository;
use Modules\Task\Models\Task;
use Modules\Team\Models\Team;
use Modules\UserActivity\Models\UserActivity;
use Modules\User\Models\User\User;
use Storage;
use Modules\Invoice\Models\Invoice;


/**
 * Class ProjectRepository
 *
 * Projects CRUD functionality
 *
 * Projects create, update, delete and view
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Projects
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectRepository
{
    protected $helperRepo;
    protected $emailRepo;
    protected $slackRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param HelperRepository $helperRepo [Object]
     * @param EmailsHelper     $emailRepo  [Object]
     *
     * @return void
     */
    public function __construct(
        HelperRepository $helperRepo,
        EmailsHelper $emailRepo,
        SlackRepository $slackRepo
    ) {
        $this->helperRepo = $helperRepo;
        $this->emailRepo = $emailRepo;
        $this->slackRepo = $slackRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Project::orderBy('id', 'desc')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function getMyProjects()
    {
        $user = Auth::user();
        return $user->projects(true)->orderBy('id', 'desc')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get projects]
     *
     * @return Object
     */
    public function getAllProjects($request)
    {
        $project_table = config('core.acl.projects_table');
        $user_table = config('core.acl.users_table');
        $team_table = config('core.acl.teams');

        if ($request->get('isUserProfile') && $request->has('user_id')) {
            $user = User::findOrFail($request->get('user_id'));
            if ($user->is_client) {
                $projects = $user->projects();
            } else {
                $projects = $user->projects(true);
            }

            $columns = array(
                0 => $project_table . '.id',
                1 => $project_table . '.project_name',
                2 => $project_table . '.start_date',
                3 => $project_table . '.end_date',
                4 => $project_table . '.progress',
                5 => $project_table . '.status',
            );
        } else {
            $user = Auth::user();
            $projects = $user->projects();

            $columns = array(
                0 => $project_table . '.id',
                1 => $project_table . '.id',
                2 => $project_table . '.project_name',
                3 => $project_table . '.start_date',
                4 => $project_table . '.progress',
                5 => 'project_created.firstname',
                6 => $project_table . '.id',
                7 => $project_table . '.status',
            );
        }

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $projects = $projects->with([
            'users' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
        ])
            ->select(
                $project_table . '.*',
                $user_table . '.firstname as client_firstname',
                $user_table . '.lastname as client_lastname',
                'project_created.firstname as created_firstname',
                'project_created.lastname as created_lastname',
                'project_created.avatar as created_avatar',
                $team_table . '.team_name'
            )
            ->leftjoin($user_table, $user_table . '.id', '=', $project_table . '.client_id')
            ->join($user_table . ' as project_created', 'project_created.id', '=', $project_table . '.user_id')
            ->leftjoin($team_table, $team_table . '.id', '=', $project_table . '.assign_to');

        if (isset($input['statusId']) && $input['statusId']) {
            if ($input['statusId'] == 6) {
                // Overdue
                $projects = $projects->whereIn($project_table . '.status', [1, 2, 3])
                    ->whereDate($project_table . '.end_date', '<', Carbon::now());
            } else {
                $projects = $projects->where($project_table . '.status', $input['statusId']);
            }
        }

        $totalData = $projects->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $projects = $projects->where(
                function ($query) use ($search, $project_table, $user_table, $team_table) {
                    $query->where($project_table . '.project_name', 'LIKE', "%{$search}%")
                        ->orWhere($project_table . '.generated_id', 'LIKE', "%{$search}%")
                        ->orWhere($project_table . '.start_date', 'LIKE', "%{$search}%")
                        ->orWhere($project_table . '.end_date', 'LIKE', "%{$search}%")
                        ->orWhere($project_table . '.progress', 'LIKE', "%{$search}%")
                        ->orWhere(DB::raw('concat(project_created.firstname," ",project_created.lastname)'), 'LIKE', "%{$search}%")
                    // ->orWhere(DB::raw("(DATE_FORMAT(capl_projects.created_at,'%Y-%m-%d'))"), 'LIKE', "%{$search}%")
                    ;
                }
            );

            $totalFiltered = $projects->count();
        }

        $data = $projects->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $statusCount = $this->_getAllProjectCount();

        return array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "statusCount" => $statusCount,
            "data" => $data,
        );
    }

    /**
     * Get all project status wise count.
     *
     * @return json
     */
    public function _getAllProjectCount()
    {
        $user = Auth::user();
        $result['all'] = $user->projects()->whereIn('status', [1, 2, 3, 4, 5])->count();
        $result['open'] = $this->_getStatusWiseCount(1, $user);
        $result['inProgress'] = $this->_getStatusWiseCount(2, $user);
        $result['onHold'] = $this->_getStatusWiseCount(3, $user);
        $result['cancel'] = $this->_getStatusWiseCount(4, $user);
        $result['completed'] = $this->_getStatusWiseCount(5, $user);
        $result['overdue'] = $user->projects()
            ->whereIn('status', [1, 2, 3])
            ->whereDate('end_date', '<', Carbon::now())
            ->count();
        return $result;
    }

    /**
     * Project status wise counting.
     *
     * @param Int    $status [Status id]
     * @param Object $user   [User object]
     *
     * @return count
     */
    private function _getStatusWiseCount($status, $user)
    {
        return $user->projects()->where('status', $status)->count();
    }

    /**
     * Get project members and version.
     *
     * @return Object
     */
    public function getProjectMembers()
    {
        $user = Auth::user();
        $project_table = config('core.acl.projects_table');
        $projects = $user->projects(true)
            ->with([
                'users' => function ($query) {
                    $query->select('id', 'firstname', 'lastname')->where('edit', true);
                },
            ])
            ->select('id', 'project_name', 'project_version', 'assign_members', 'estimated_hours', 'start_date', 'end_date')
            ->orderBy('id', 'desc')
            ->get();
        return $projects;
    }

    /**
     * Display a project of the resource.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        $user = Auth::user();
        $project = Project::with([
            'createdUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'clients' => function ($query) {
                $query->select('id', 'firstname', 'lastname');
            },
            // 'sprints',
            'tasks' => function ($query) {
                $query->with([
                    'assignUser' => function ($q) {
                        $q->select('id', 'firstname', 'lastname', 'avatar');
                    },
                ]);
            },
            'defects.assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'incidents.assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'comments.user' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'attachments',
            'users' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'email', 'avatar');
            },
            'team' => function ($query) {
                $query->select('id', 'team_name');
            }
        ])
            ->where('id', $id)
            ->first();

        if ($project) {
            $result = $project;
            $result['current_user_id'] = $user->id;
            $assign_members = explode(",", $project['assign_members']);
            $result['assign_members'] = User::select('id', 'firstname', 'lastname', 'avatar')
                ->whereIn('id', $assign_members)
                ->get();

            $result['custom_fields'] = CustomField::getViewFields(1)->get();
            $result['activities'] = UserActivity::getActivities(['Project', 'ProjectComment', 'ProjectAttachment'], $id)->get();

            return $result;
        }
        return false;
    }

    /**
     * Display a project of the resource.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function getProjectById($id)
    {
        $project = Project::with([
            'users' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'email', 'avatar');
            },
            'createdUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'clients' => function ($query) {
                $query->select('id', 'firstname', 'lastname');
            },
        ])
            ->where('id', $id)
            ->first();

        if ($project) {
            $result = $project;
            $result['custom_fields'] = CustomField::getViewFields(1)->get();
            return $result;
        }

        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create project]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $project = new Project;
        $input = $request->all();
        $user = Auth::user();
        $input['user_id'] = $user->id;
        $input['generated_id'] = $this->getGeneratedId();
        $assignMembers = [];

        if (isset($input['assign_members'])
            && is_array($input['assign_members'])
            && count($input['assign_members']) > 0
        ) {
            foreach ($input['assign_members'] as $value) {
                $assignMembers[] = $value;
            }
            $input['assign_members'] = implode(",", $assignMembers);
        } else {
            $input['assign_members'] = 'Unassign';
        }

        $userIds = [];
        if (!empty($input['users'])) {
            foreach ($input['users'] as $value) {
                $userIds[] = $value;
            }
        }

        $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();
        $userIds = array_merge($userIds, $super_admin_ids);
        array_push($userIds, $user->id); // login user
        if ($input['client_id']) {
            array_push($userIds, $input['client_id']);
        }
        $userIds = array_unique($userIds);

        $assignMembers = array_merge($assignMembers, $super_admin_ids);
        array_push($assignMembers, $user->id); // login user
        // if ($input['client_id']) {
        //     array_push($assignMembers, $input['client_id']);
        // }
        $assignMembers = array_unique($assignMembers);

        if (!empty($input['project_logo'])) {
            $input['project_logo'] = $this->_uploadImage($input['project_logo']);
        }

        $projects = $project->create($input);
        if ($projects) {
            // --
            // Save custom field
            if (!empty($projects) && isset($input['custom_fields'])) {
                $this->helperRepo->saveCustomField(
                    1,
                    $projects['id'],
                    $input['custom_fields']
                );
            }

            // --
            // Add activities
            createUserActivity(
                Project::MODULE_NAME,
                $projects['id'],
                $request->method(),
                $input['project_name'],
                $request->ip(),
                $projects['id']
            );

            // --
            // Post message to slack
            $project = Project::findOrFail($projects['id']);
            $this->slackRepo->postActivitiesMessage('Project Created Successfully', $project, 'Project');

            // --
            // User sync
            if ($projects->users()->sync($userIds)) {
                $projects->users()->updateExistingPivot($assignMembers, ['edit' => true, 'delete' => true]);
                // if ($input['client_id']) {
                //     $assignMembers = array_diff($assignMembers, [$input['client_id']]); // Unset client
                // }
                $userData = User::select(DB::raw("CONCAT(firstname,' ',lastname) as name"), 'email')
                    ->whereIn('id', $assignMembers)
                    ->get();

                if (!empty($userData)) {
                    $this->emailRepo->sendProjectAssignUserEmail($userData, $project);

                    // Add Notification.
                    $this->createNotification($project->id, 'Project Created.', $project->project_name);
                }

                // --
                // Send client mail
                if ($input['client_id']) {
                    $this->emailRepo->sendCreateProjectNotiClientMail($project);
                }

                $this->_sendProjectStartReminder($project);

                return $projects['id'];
            }
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update project]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $project = Project::findOrFail($id);
        $input = $request->all();
        $user = Auth::user();
        $assignMembers = [];

        // --
        //Set all task completed when status of project is completed.
        if ($input['status'] == 5) {
            $this->_updateProjectTaskStatus($id);
            $input['progress'] = 100;
        }

        if (!empty($input['project_logo']) && $project->project_logo != $input['project_logo']) {
            $input['project_logo'] = $this->_uploadImage($input['project_logo']);

            if ($project->project_logo && !in_array($project->project_logo, $input['ProjectLogos'])) {
                $this->_deleteFile($project->project_logo);
            }
        }

        $old_version = $project->project_version;
        $current_version = explode(',', $old_version);
        $current_version = end($current_version);
        $input['project_version'] = explode(',', $input['project_version']);
        $input['project_version'] = end($input['project_version']);
        if ($input['project_version'] != $current_version) {
            $input['project_version'] = $old_version . ',' . $input['project_version'];
        } else {
            $input['project_version'] = $old_version;
        }

        if (is_array($input['assign_members']) && count($input['assign_members']) > 0) {
            foreach ($input['assign_members'] as $value) {
                $assignMembers[] = $value['id'];
                // $syncUser[$value['id']] = ['edit' => true];
            }
            $input['assign_members'] = implode(",", $assignMembers);
        } elseif ($input['assign_members'] == null) {
            $input['assign_members'] = 'Unassign';
        } elseif($input['assign_members']){
            // For inline edit.
            $assignMembers = explode(",", $input['assign_members']);
        }

        $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();

        $userIds = [];
        if (!empty($input['users'])) {
            foreach ($input['users'] as $value) {
                $userIds[] = $value['id'];
            }
        }
        $userIds = array_merge($userIds, $super_admin_ids);
        array_push($userIds, $user->id); // login user
        if ($input['client_id']) {
            array_push($userIds, $input['client_id']);
        }
        $userIds = array_unique($userIds);

        $assignMembers = array_merge($assignMembers, $super_admin_ids);
        array_push($assignMembers, $user->id); // login user
        // if ($input['client_id']) {
        //     array_push($assignMembers, $input['client_id']);
        // }
        $assignMembers = array_unique($assignMembers);

        if ($project->fill($input)->save()) {
            // --
            // Save custom field
            if (isset($input['custom_fields'])) {
                $this->helperRepo->saveCustomField(1, $id, $input['custom_fields']);
            }

            // --
            // Add activities
            createUserActivity(
                Project::MODULE_NAME,
                $project->id,
                $request->method(),
                $project->project_name,
                $request->ip(),
                $project->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Project Updated Successfully', $project, 'Project');

            foreach ($userIds as $key => $value) {
                if (in_array($value, $assignMembers)) {
                    $syncUser[$value] = ['edit' => true, 'delete' => true];
                } else {
                    $syncUser[$value] = ['edit' => false, 'delete' => false];
                }
            }
            // $user->roles()->sync([1 => ['edit' => true], 2, 3]);
            if ($project->users()->sync($syncUser)) {

                // $projectUser = $project->users()->wherePivot('edit', 1)->pluck('id')->toArray();
                // $mailUsers = array_diff($assignMembers, $projectUser);

                $userData = User::select(DB::raw("CONCAT(firstname,' ',lastname) as name"), 'email')
                    ->whereIn('id', $assignMembers) // assignMembers = All project member, mailUsers = New assign member
                    ->where('is_client', 0)
                    ->where('is_active', 1)
                    ->get();
                if (!empty($userData)) {
                    $this->emailRepo->sendProjectUpdateEmail($userData, $project);

                    // Add Notification.
                    $this->createNotification($project->id, 'Project Updated.', $project->project_name);
                }

                $this->_sendProjectStartReminder($project);

                return true;
            }
        }
        return false;
    }

    /**
     * Send project start reminder.
     *
     * @param Object $project [Project object]
     *
     * @return void
     */
    private function _sendProjectStartReminder($project)
    {
        $setting = Setting::select('reminder_alert_before_days')->first();
        $beforeDays = explode(',', $setting->reminder_alert_before_days);

        foreach ($beforeDays as $days) {
            $reminderDate = Carbon::now()->addDays($days)->format('Y-m-d');
            if ($project->start_date->format('Y-m-d') == $reminderDate) {
                $this->emailRepo->sendProjectReminderEmails($project);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update project notes]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function projectNotesUpdate($request, $id)
    {
        $input = $request->all();
        $project = Project::findOrFail($id);

        if ($project->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                Project::MODULE_NAME,
                $project->id,
                $request->method(),
                $project->project_name,
                $request->ip(),
                $project->id
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete project]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $project = Project::findOrFail($id);
        if ($project) {
            $input = $request->all();
            if ($project->project_logo && !in_array($project->project_logo, $input['ProjectLogos'])) {
                $this->_deleteFile($project->project_logo);
            }

            // Add Notification.
            $this->createNotification($project->id, 'Project Deleted.', $project->project_name, 'DELETE');

            $project->users()->detach();

            if ($project->delete()) {
                // --
                // Add activities
                createUserActivity(
                    Project::MODULE_NAME,
                    $project->id,
                    'DELETE',
                    $project->project_name,
                    $request->ip(),
                    $project->id
                );

                // --
                // Post message to slack
                $this->slackRepo->postActivitiesMessage('Project Deleted Successfully', $project, 'Project');

                return true;
            }
        }
        return false;
    }

    /**
     * Change project status.
     *
     * @param Request $request [Request for change project status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeProjectStatus($request, $id)
    {
        $statusId = $request->get('status');
        $project = Project::with([
            'users' => function ($query) {
                $query->where('is_active', true)->wherePivot('edit', true);
            },
        ])->findOrFail($id);
        if ($project) {
            $input['status'] = $statusId;
            if ($statusId == 5) {
                $this->_updateProjectTaskStatus($id);
                $input['progress'] = 100;
                $input['end_date'] = Carbon::now();
            }

            if ($project->fill($input)->save()) {
                if ($statusId == 5) {
                    $this->emailRepo->sendProjectCompletedNotificationClientMail($project, $project->id);
                }

                $this->emailRepo->sendProjectStatusChangeEmails($project);

                // Add Notification.
                $this->createNotification($project->id, 'Project Status Changed.', $project->project_name);

                // --
                // Add activities
                createUserActivity(
                    Project::MODULE_NAME,
                    $project->id,
                    $request->method(),
                    $project->project_name,
                    $request->ip(),
                    $project->id,
                    true
                );

                // --
                // Post message to slack
                $this->slackRepo->postActivitiesMessage('Project Status Changed Successfully', $project, 'Project');
            }

            return true;
        }
        return false;
    }

    /**
     * Retrive generated id.
     *
     * @return String
     */
    public function getGeneratedId()
    {
        return 'P' . str_pad(nextAutoID(config('core.acl.projects_table')), 5, "0", STR_PAD_LEFT);
    }

    /**
     * Store a newly imported resource in storage.
     *
     * @param Request $request [Request for import project]
     * @param Array   $csvData [Project Data]
     *
     * @return Boolean
     */
    public function createImportProject($request, $csvData)
    {
        $success = 0;
        $unsuccess = 0;
        $result = [];
        $status = [
            1 => 'open',
            2 => 'in progress',
            3 => 'on hold',
            4 => 'cancel',
            5 => 'completed',
        ];
        unset($csvData[0]); //Unset header.
        foreach ($csvData as $key => $value) {
            $value[8] = strtolower(trim($value[8])); // status
            $team = Team::with([
                'members' => function ($query) {
                    $query->select(
                        config('core.acl.users_table') . '.id',
                        config('core.acl.users_table') . '.email'
                    );
                },
            ])
                ->where('team_name', trim($value[6]))
                ->first();

            $client = User::whereEmail(trim($value[14]))->where('is_client', 1)->first();

            $login_user = User::select('id', 'firstname', 'lastname')
                ->whereEmail(trim($value[15]))
                ->first();

            $value["start_date"] = date("Y-m-d", strtotime(trim($value[2])));
            $value["end_date"] = date("Y-m-d", strtotime(trim($value[3])));

            $validationError[$key] = $this->_validateProject(
                $value,
                $team,
                $client,
                $login_user,
                $status
            );

            if (empty($validationError[$key])) {
                $input['generated_id'] = $this->getGeneratedId();
                $input['project_name'] = trim($value[0]);
                $input['project_version'] = empty($value[1]) ? '1.0' : trim($value[1]);
                $input['start_date'] = $value['start_date'];
                $input['end_date'] = $value['end_date'];
                $input['progress'] = empty($value[4]) ? 0 : trim($value[4]);
                $input['demo_url'] = trim($value[5]);
                $input['assign_to'] = ($team) ? $team->id : null;

                $assignMembers = [];
                if (!empty($value[7])) {
                    $assignMembers = User::whereIn('email', explode("-", trim($value[7])))->pluck('id')->toArray();
                    $input['assign_members'] = implode(",", $assignMembers);
                } else {
                    $input['assign_members'] = 'Unassign';
                }

                $input['status'] = array_search($value[8], $status);

                $billing_type = strtolower(trim($value[9]));
                if ($billing_type == 'fixed rate') {
                    $input['billing_type'] = 1;
                    $input['price_rate'] = trim($value[10]);
                }
                if ($billing_type == 'hourly rate') {
                    $input['billing_type'] = 2;
                    $input['price_rate'] = trim($value[11]);
                }

                $input['estimated_hours'] = empty($value[12]) ? '' : trim($value[12]);
                $input['description'] = trim($value[13]);
                $input['client_id'] = ($client) ? $client->id : null;
                $input['user_id'] = $login_user->id;

                // --
                // Save project
                $project = new Project;
                $project = $project->create($input);
                if ($project) {
                    // --
                    // Add activities
                    createUserActivity(
                        Project::MODULE_NAME,
                        $project['id'],
                        $request->method(),
                        $input['project_name'],
                        $request->ip(),
                        $project['id']
                    );

                    $userIds = [];
                    if ($team) {
                        foreach ($team->members as $member) {
                            $userIds[] = $member->id;
                        }
                    }

                    $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();
                    $userIds = array_merge($userIds, $super_admin_ids);
                    array_push($userIds, $login_user->id); // login user
                    if ($client) {
                        array_push($userIds, $client->id); // client
                    }
                    $userIds = array_unique($userIds);

                    $assignMembers = array_merge($assignMembers, $super_admin_ids);
                    array_push($assignMembers, $login_user->id); // login user
                    if ($client) {
                        array_push($assignMembers, $client->id);
                    }
                    $assignMembers = array_unique($assignMembers);

                    if ($project->users()->sync($userIds)) {
                        $project->users()->updateExistingPivot($assignMembers, ['edit' => true, 'delete' => true]);

                        if ($client) {
                            $assignMembers = array_diff($assignMembers, [$client->id]); // Unset client
                        }
                        $userData = User::select(DB::raw("CONCAT(firstname,' ',lastname) as name"), 'email')
                            ->whereIn('id', $assignMembers)
                            ->get();

                        if (!empty($userData)) {
                            $this->emailRepo->sendProjectAssignUserEmail($userData, $project);
                        }
                        // --
                        // Send client mail
                        if ($client) {
                            $this->emailRepo->sendCreateProjectNotiClientMail($project);
                        }
                    }
                }

                $result[$key] = 'success';
                $success++;
            } else {
                $result[$key] = $validationError[$key];
                $unsuccess++;
            }
        }

        return array(
            "result" => $result,
            "success" => intval($success),
            "unsuccess" => intval($unsuccess),
            "total" => intval($unsuccess) + intval($success),
        );
    }

    /**
     * Validate imported project.
     *
     * @param Array  $data       [Row data]
     * @param Object $team       [Team object]
     * @param Object $client     [Client object]
     * @param Object $login_user [User object]
     * @param Object $status     [Status array]
     *
     * @return Array
     */
    private function _validateProject($data, $team, $client, $login_user, $status)
    {
        $error = array();

        if (empty($data[0])) {
            $error[] = 'Project name is required.';
        } elseif (strlen($data[0]) > config('core.max_length')) {
            $error[] = 'The project name may not be greater than ' . config('core.max_length') . ' characters.';
        } else {
            $project = Project::where('project_name', trim($data[0]))->exists(); // Allowed softdeleted.
            if ($project) {
                $error[] = 'The project name has already been taken.';
            }
        }

        if (empty($data[2])) {
            $error[] = 'Start date is required.';
        } elseif (false === strtotime($data[2])) {
            $error[] = 'Please enter valid start date.';
        }

        if (empty($data[3])) {
            $error[] = 'End date is required.';
        } elseif (false === strtotime($data[3])) {
            $error[] = 'Please enter valid end date.';
        } elseif ($data['end_date'] < $data['start_date']) {
            $error[] = 'The project end date must be a date after or equal to project start date.';
        }

        if (!empty($data[6]) && !$team) {
            $error[] = 'Assign group is not exists.';
        } elseif (!empty($data[7]) && !$team) {
            $error[] = 'Assign group is required.';
        }
        if (!empty($data[7]) && $team) {
            foreach ($team->members as $key => $value) {
                $teamMem[] = $value->email;
            }
            $members = explode("-", trim($data[7]));
            foreach ($members as $key => $value) {
                if (!in_array($value, $teamMem)) {
                    $error[] = $value . ' is not a team member';
                }
            }
        }

        if (empty($data[8])) {
            $error[] = 'Status is required.';
        } elseif (!in_array($data[8], $status)) {
            $error[] = 'Please enter valid status.';
        }

        if (!empty($data[12])) {
            if (preg_match('/^[0-9]+\:[0-5][0-9]$/', $data[12]) !== 1) {
                $error[] = 'The estimated hours allow only digits, 2 digits after colon(less than 60) without any special characters.';
            }
        }

        if (!empty($data[14]) && !$client) {
            $error[] = 'Client is not exists.';
        }

        if (empty($data[15])) {
            $error[] = 'Created by is required.';
        } elseif (!$login_user) {
            $error[] = 'Created by is not exists';
        }

        return $error;
    }

    /**
     * Update project task status.
     *
     * @param Int $id [Row id]
     *
     * @return void
     */
    private function _updateProjectTaskStatus($id)
    {
        Task::where('project_id', '=', $id)->update(['progress' => 100, 'status' => 6]);
    }

    /**
     * Image upload.
     *
     * @param String $imageData [Image base64]
     *
     * @return String
     */
    private function _uploadImage($imageData)
    {
        $image_parts = explode(";base64,", $imageData);
        if (count($image_parts) >= 2) {
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_base64 = base64_decode($image_parts[1]);
            $filename = uniqid() . '.' . $image_type_aux[1];
            if (!Storage::disk('project_uploads')->put($filename, $image_base64)) {
                return null;
            } else {
                return $filename;
            }
        } else {
            return $imageData;
        }
    }

    /**
     * Delete Image.
     *
     * @param String $fileName [File name]
     *
     * @return Boolean
     */
    private function _deleteFile($fileName)
    {
        $file = public_path() . '/uploads/project/' . $fileName;
        if (file_exists($file)) {
            unlink($file);
        }
        return true;
    }

    /**
     *  Get project planner project, sprint, tasks
     *
     * @param Request $request [Request for get projects]
     *
     * @return Response
     */
    public function getProjectSprintTasks($request)
    {
        $users = Auth::user();
        $user_table = config('core.acl.users_table');
        $project_table = config('core.acl.projects_table');
        $input = $request->input();

        $projects = $users->projects()
            ->with([
                'users' => function ($query) use ($user_table) {
                    $query->select(
                        $user_table . '.id',
                        $user_table . '.firstname',
                        $user_table . '.lastname',
                        $user_table . '.avatar'
                    );
                },
                'createdUser' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'avatar');
                },
                // 'sprints.sprintMembers' => function ($query) use ($user_table) {
                //     $query->select(
                //         $user_table.'.id',
                //         $user_table.'.firstname',
                //         $user_table.'.lastname',
                //         $user_table.'.avatar'
                //     );
                // },
                // 'sprints.tasks.taskMembers' => function ($query) use ($user_table) {
                //     $query->select(
                //         $user_table.'.id',
                //         $user_table.'.firstname',
                //         $user_table.'.lastname',
                //         $user_table.'.avatar'
                //     );
                // }
            ])
            ->withCount('sprints');

        if (isset($input['statusId']) && $input['statusId']) {
            if ($input['statusId'] == 6) {
                // Overdue
                $projects = $projects->whereIn($project_table . '.status', [1, 2, 3])
                    ->whereDate($project_table . '.end_date', '<', Carbon::now());
            } else {
                $projects = $projects->where($project_table . '.status', $input['statusId']);
            }
        }

        $projects = $projects->orderBy('id', 'desc')->get();
        $statusCount = $this->_getAllProjectCount();

        foreach ($projects as $key => $value) {

            $sprintIds = ProjectPlannerSprint::where("project_id", $value->id)->pluck("id");

            $sprintTaskCount = ProjectSprintTask::whereIn('project_sprint_id', $sprintIds)
                ->where('type', 'Task')
                ->whereNotIn('status', [4])
                ->count();

            $sprintStoryCount = ProjectSprintTask::whereIn('project_sprint_id', $sprintIds)
                ->where('type', 'Story')
                ->count();

            $nonSprintTaskCount = ProjectSprintTask::where('project_id', $value->id)
                ->where('type', 'Task')
                ->whereNotIn('status', [4])
                ->whereNull('project_sprint_id')
                ->count();

            $nonSprintStoryCount = ProjectSprintTask::where('project_id', $value->id)
                ->where('type', 'Story')
                ->whereNull('project_sprint_id')
                ->count();

            $projects[$key]->sprintTaskCount = $sprintTaskCount + $nonSprintTaskCount;
            $projects[$key]->sprintStoryCount = $sprintStoryCount + $nonSprintStoryCount;
        }

        return array(
            "statusCount" => $statusCount,
            "projects" => $projects,
        );
    }

    /**
     * Display Project all data.
     *
     * @param Request $request [Request for get project report]
     *
     * @return Array
     */
    public function getProjectForReport($request)
    {
        $project_table = config('core.acl.projects_table');
        $user_table = config('core.acl.users_table');
        $user = Auth::user();

        $columns = array(
            0 => $project_table . '.generated_id',
            1 => $project_table . '.project_name',
            // 2 => $project_table . '.project_version',
            2 => $project_table . '.start_date',
            3 => $project_table . '.end_date',
            // 5 => $project_table . '.actual_hours',
            4 => $project_table . '.progress',
            5 => DB::raw('concat(project_created.firstname," ",project_created.lastname)'),
            6 => $project_table . '.assign_members',
            7 => $project_table . '.status',
            8 => $project_table . '.billing_type',
            9 => $project_table . '.price_rate',
            10 => DB::raw("concat($user_table.firstname,' ',$user_table.lastname)"),
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $columns_search = $request->input('columns');

        $projects = $user->projects()->with(
            [
                'users' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'avatar');
                },
            ]
        )
            ->select(
                $project_table . '.*',
                'project_created.id as created_id',
                'project_created.firstname as created_firstname',
                'project_created.lastname as created_lastname',
                'project_created.avatar as created_avatar',
                $user_table . '.firstname as client_firstname',
                $user_table . '.lastname as client_lastname',
                $user_table . '.avatar as client_avatar'
            )
            ->join($user_table . ' as project_created', 'project_created.id', '=', $project_table . '.user_id')
            ->leftjoin($user_table, $user_table . '.id', '=', $project_table . '.client_id');

            $matchThese = [];
            $start_date_query = [];
            $end_date_query = [];
            $start_date_query_ranged = [];
            $end_date_query_ranged = [];
        
            foreach ((array) $columns_search as $key => $value) {
                if (!empty($value['search']['value'])) {

                    if ($columns[$key] == $project_table . '.start_date') {
                         array_push($start_date_query_ranged,[$columns[$key], '>=', "{$value['search']['value']}"]);
                         array_push($start_date_query,[$columns[$key], 'LIKE', "%{$value['search']['value']}%"]);
                    } 
                    elseif ($columns[$key] == $project_table . '.end_date') {
                        array_push($end_date_query_ranged,[$columns[$key], '<=', "{$value['search']['value']}"]);
                        array_push($end_date_query,[$columns[$key], 'LIKE', "%{$value['search']['value']}%"]);
                    } 
                    else {
                        array_push(
                            $matchThese,
                            [$columns[$key], 'LIKE', "%{$value['search']['value']}%"]
                        );
                    }
                }
            }
        
            // Check if start_date and end_date queries are present
            $start_date_present = !empty($start_date_query);
            $end_date_present = !empty($end_date_query);
                       
        
            $totalData = $projects->count();
            $totalFiltered = $totalData;
        
            // Check if start_date and end_date queries are present then apply ranged queries, else apply normal queries
            if($start_date_present && $end_date_present){
                $projects = $projects->where($start_date_query_ranged);
                $projects = $projects->where($end_date_query_ranged);
                $projects = $projects->where($matchThese);

                $totalFiltered = $projects->count();
            }
            elseif (!empty($matchThese)) {
                $projects = $projects->where($matchThese);
                $projects = $projects->where($start_date_query);
                $projects = $projects->where($end_date_query);

                $totalFiltered = $projects->count();
            }
        

        $data = $projects->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        // Retrieve all invoices for the user
        $invoices = Invoice::select(
            'project_id',
            'client_id',
            'total_amount',
            'total_due_amount'
        );

        // if (!($user->hasRole('admin') || $user->is_super_admin)) {
        //     // Add conditions for non-admin users
        //     $invoices->whereHas('project', function ($query) use ($user) {
        //         $query->where('user_id', $user->id);
        //     });
        // }

        $allInvoices = $invoices->get();

        // Initialize sum variables
        $totalAmountSum = 0;
        $totalDueAmountSum = 0;

       // Iterate over each project in the $data collection
        $data->each(function ($project) use ($allInvoices, &$totalAmountSum, &$totalDueAmountSum) {
            // Filter invoices based on the project_id
            $matchingInvoices = $allInvoices->where('project_id', $project->id);

            // Calculate the sum of 'total_amount' and 'total_due_amount'
            $totalAmountSum = $matchingInvoices->sum('total_amount');
            $totalDueAmountSum = $matchingInvoices->sum('total_due_amount');

            // Add additional fields to the existing project object
            $project->total_amount = $totalAmountSum;
            $project->total_due_amount = $totalDueAmountSum;
        });

            return array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
            );
    }

    /**
     * Check user permission.
     *
     * @param Int    $project_id [Project Id]
     * @param String $action     [Action For]
     *
     * @return Boolean
     */
    public function checkPermission($project_id, $action)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $projectUser = ProjectUser::where('project_id', $project_id)
            ->where('user_id', Auth::user()->id)
            ->where($action, true)
            ->first();

        if ($projectUser) {
            return true;
        }
        return false;
    }

    /**
     * Create notification.
     *
     * @param Int    $id [Project Id]
     * @param String $title     [Title]
     * @param String $message     [Message]
     * @param String $method     [Method]
     *
     * @return Boolean
     */
    private function createNotification($id, $title, $message, $method = null)
    {
        $project = Project::with(['users' => function ($query) {
            $query->select('id', 'firstname', 'lastname')->wherePivot('edit', true);
        }])->find($id);

        createNotification($project->users, 'projects', $project->id, $title, "#" . $project->generated_id . ' - ' . $message, $method);
        if($project->clients){
            createNotification([$project->clients], 'projects', $project->id, $title, "#" . $project->generated_id . ' - ' . $message, $method);
        }
    }
}
