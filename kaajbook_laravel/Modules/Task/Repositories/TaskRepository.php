<?php
namespace Modules\Task\Repositories;

use Auth;
use Carbon\carbon;
use DB;
use Modules\CustomField\Models\CustomField;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Repositories\HelperRepository;
use Modules\Projects\Models\Project;
use Modules\Setting\Models\Setting;
use Modules\Slack\Repositories\SlackRepository;
use Modules\Task\Models\Task;
use Modules\Team\Models\Team;
use Modules\Timesheet\Models\Timesheet;
use Modules\UserActivity\Models\UserActivity;
use Modules\User\Models\User\User;

/**
 * Class TaskRepository
 *
 * Task create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Task
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TaskRepository
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
     * @param Request $request [Request for get task]
     *
     * @return Array
     */
    public function getAllTask($request)
    {
        $task_table = config('core.acl.task_table');
        $user_table = config('core.acl.users_table');
        $user = Auth::user();

        $columns = array(
            0 => $task_table . '.id',
            1 => $task_table . '.name',
            2 => $task_table . '.task_start_date',
            3 => $task_table . '.task_end_date',
            4 => $task_table . '.actual_hours',
            5 => $user_table . '.firstname',
            6 => $task_table . '.priority',
            7 => $task_table . '.status',
        );

        if ($request->get('isUserProfile') && $request->has('user_id')) {
            $task = Task::where($task_table . '.assign_to', $request->get('user_id'));
            $statusCount = [];

            $columns = array(
                0 => $task_table . '.id',
                1 => $task_table . '.name',
                2 => $task_table . '.task_start_date',
                3 => $task_table . '.task_end_date',
                4 => $task_table . '.progress',
                5 => $task_table . '.priority',
                6 => $task_table . '.status',
            );
        } elseif ($request->has('filter') && $request->get('filter') === "selected") {
            $task = Task::where(function ($query) use ($task_table, $user) {
                $query->where($task_table . '.assign_to', $user->id)
                    ->orWhere($task_table . '.created_by', $user->id);
            });
            $statusCount = $this->_getTaskCount(true);
        } else {
            $task = $user->tasks();
            $statusCount = $this->_getTaskCount();
        }

        $statusId = $request->get('status');
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $task = $task->leftjoin($user_table, $user_table . '.id', '=', $task_table . '.assign_to')
            ->select(
                $task_table . '.*',
                $user_table . '.firstname as assign_firstname',
                $user_table . '.lastname as assign_lastname',
                $user_table . '.avatar as assign_avatar'
            );

        // --
        // Status
        // 1=Open,2=InProgress,3=OnHold,4=Waiting,5=Cancel,6=Completed
        if ($statusId != 0) {
            if ($statusId == 1) {
                $task->whereIn($task_table . '.status', [1]);
            } elseif ($statusId == 2) {
                $task->whereIn($task_table . '.status', [2, 3, 4]);
            } elseif ($statusId == 6) {
                $task->whereIn($task_table . '.status', [5, 6]);
            } elseif ($statusId == 7) {
                // Overdue
                $task->whereIn($task_table . '.status', [1, 2, 3, 4])
                    ->whereDate($task_table . '.task_end_date', '<', Carbon::now());
            }
        }

        $totalData = $task->count();
        $totalFiltered = $totalData;

        // --
        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $task = $task->where(function ($query) use ($search, $task_table, $user_table) {
                $query->where($task_table . '.name', 'LIKE', "%{$search}%")
                    ->orWhere($task_table . '.generated_id', 'LIKE', "%{$search}%")
                    ->orWhere($task_table . '.task_start_date', 'LIKE', "%{$search}%")
                    ->orWhere($task_table . '.task_end_date', 'LIKE', "%{$search}%")
                    ->orWhere($task_table . '.actual_hours', 'LIKE', "%{$search}%")
                    ->orWhere($task_table . '.status', 'LIKE', "%{$search}%")
                    ->orWhere($task_table . '.priority', 'LIKE', "%{$search}%")
                    ->orWhere($task_table . '.project_version', 'LIKE', "%{$search}%")
                    ->orWhere(
                        DB::raw('concat(' . $user_table . '.firstname," ",' . $user_table . '.lastname)'),
                        'LIKE',
                        "%{$search}%"
                    );
            });

            $totalFiltered = $task->count();
        }

        $data = $task->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        return array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "statusCount" => $statusCount,
            "data" => $data,
        );
    }

    /**
     * Get all status type count.
     *
     * @param Boolean     $isMy      [For all task or user task]
     *
     * @return Array
     */
    private function _getTaskCount($isMy = false)
    {
        $user = Auth::user();
        $result['all'] = $this->_getStatusWiseCount([1, 2, 3, 4, 5, 6], $user, $isMy);
        if ($result['all'] > 0) {
            $result['open'] = $this->_getStatusWiseCount([1], $user, $isMy);
            $result['in_progress'] = $this->_getStatusWiseCount([2, 3, 4], $user, $isMy);
            $result['completed'] = $this->_getStatusWiseCount([5, 6], $user, $isMy);
            $result['overdue'] = $this->_getOverdueCount($user, $isMy);
        }
        return $result;
    }

    /**
     * Task status wise counting.
     *
     * @param Array  $status [Task status id]
     * @param Object $user   [User object]
     * @param Boolean $isMy  [For all task or user task]
     *
     * @return Count
     */
    private function _getStatusWiseCount($status, $user, $isMy)
    {
        if ($isMy) {
            return Task::where(function ($query) use ($user) {
                $query->where('assign_to', $user->id)
                    ->orWhere('created_by', $user->id);
            })
                ->whereIn('status', $status)
                ->count();
        } else {
            return $user->tasks()
                ->whereIn('status', $status)
                ->count();
        }
    }

    /**
     * Task overdue counting.
     *
     * @param Object $user   [User object]
     * @param Boolean $isMy  [For all task or user task]
     *
     * @return Count
     */
    private function _getOverdueCount($user, $isMy)
    {
        if ($isMy) {
            $overdueTask = Task::where(function ($query) use ($user) {
                $query->where('assign_to', $user->id)
                    ->orWhere('created_by', $user->id);
            });
        } else {
            $overdueTask = $user->tasks();
        }

        return $overdueTask->whereIn('status', [1, 2, 3, 4])
            ->whereDate('task_end_date', '<', Carbon::now())
            ->count();
    }

    /**
     * Retrive particular resource
     *
     * @param Int $id [Row id]
     *
     * @return Array
     */
    public function getById($id)
    {
        $task_table = config('core.acl.task_table');
        $users_table = config('core.acl.users_table');
        $task = Task::with([
            'assignUser' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname',
                    'avatar'
                );
            },
            'project1.users' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname'
                );
            },
            'comments.user' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname',
                    'avatar'
                );
            },
            'users' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname'
                );
            },
            'subTasks' => function ($query) {
                $query->with([
                    'assignUser' => function ($query) {
                        $query->select(
                            'id',
                            'firstname',
                            'lastname',
                            'avatar'
                        );
                    },
                    'createdUser' => function ($query) {
                        $query->select(
                            'id',
                            'firstname',
                            'lastname',
                            'avatar'
                        );
                    },
                ]);
            },
            'attachments',
        ])
            ->leftjoin($task_table . ' as parent', 'parent.id', '=', $task_table . '.parent_task_id')
            ->leftjoin($users_table, $task_table . '.created_by', '=', $users_table . '.id')
            ->select(
                $task_table . '.*',
                $users_table . '.firstname as created_firstname',
                $users_table . '.lastname as created_lastname',
                $users_table . '.avatar as created_avatar',
                'parent.name as parentTaskName'
            )
            ->where($task_table . '.id', '=', $id)
            ->first();

        if ($task) {
            $task['custom_fields'] = CustomField::getViewFields(2)->get();
            $task['activities'] = UserActivity::getActivities(['Task', 'TaskComment', 'TasksAttachment'], $id)->get();
        }

        return $task;
    }

    /**
     * Retrieve parent task of particular subtask.
     *
     * @param Int $parent_task_id [Parent task row id]
     *
     * @return Array
     */
    public function getParentTask($parent_task_id)
    {
        return Task::with([
            'project1.users' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname'
                );
            },
        ])->find($parent_task_id);
    }

    /**
     * Store a newly created resource in the storage.
     *
     * @param Request $request [Request for create task]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $project = Project::findOrFail($input['project_id']);

        // --
        // Add subtask hours to parent task hours.
        if (isset($input['estimated_hours'])) {
            if (isset($input['parent_task_id']) && $input['parent_task_id']) {
                $parentTask = Task::findOrFail($input['parent_task_id']);
                $parentTask->estimated_hours = $this->_getTasksEstimatehours([$parentTask->estimated_hours, $input['estimated_hours']], true);
                $parentTask->save();
            }
        }

        $user = Auth::user();
        $task = new Task;
        $input['created_by'] = $user->id;
        if(isset($input['parent_task_id']) && $input['parent_task_id'] != 0) {
            $parentTask = Task::findOrFail($input['parent_task_id']);
            $input['generated_id'] = $parentTask->generated_id.'.'.$this->getSubTaskCountByParent($input['parent_task_id']);
        }else{
            $input['generated_id'] = $this->getGeneratedId();
        }
        $input['order'] = Task::count() + 1;
        if ($input['status'] == 6) {
            $input['progress'] = 100;
        }

        $tasks = $task->create($input);

        if ($tasks) {
            // --
            // Save custom field.
            if (isset($input['custom_fields'])) {
                $this->helperRepo->saveCustomField(
                    2,
                    $tasks['id'],
                    $input['custom_fields']
                );
            }
            // --
            // Add activities
            createUserActivity(
                Task::MODULE_NAME,
                $tasks->id,
                $request->method(),
                $tasks->name,
                $request->ip(),
                $tasks->id
            );

            // --
            // Post message to slack
            $task = Task::findOrFail($tasks['id']);
            $this->slackRepo->postActivitiesMessage('Task Created Successfully', $task, 'Task');

            // --
            // User sync
            $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();
            $userIds = [];
            $userIds = $project->users()->wherePivot('edit', 1)->pluck('id')->toArray();

            $userIds = array_merge($userIds, $super_admin_ids);
            array_push($userIds, $user->id); // login user
            if ($project->client_id) {
                array_push($userIds, $project->client_id); // client
            }
            $userIds = array_unique($userIds);

            if ($tasks->users()->sync($userIds)) {
                // --
                // Send mail.
                if ($input['assign_to']) {

                    // Assign user and super admins.
                    // $mailUserId = $super_admin_ids;
                    // array_push($mailUserId, $input['assign_to']);
                    // $mailUserId = array_unique($mailUserId);

                    // All project members.
                    if ($project->client_id) {
                        $userIds = array_diff($userIds, [$project->client_id]); // Unset client
                    }

                    $this->_sendMailEveryone($userIds, $tasks);

                    $this->_sendTaskStartReminder($task);

                    // Add Notification.
                    createNotification([$task->assignUser], 'tasks', $task->id, 'Task Created.', "#" . $task->generated_id . ' - ' . $task->name);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource
     *
     * @param Request $request [Request for update task]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $task = Task::find($id);
        $project = Project::findOrFail($task->project_id);
        $input = $request->all();
        $user = Auth::user();

        // --
        // Update subtask hours to parent task hours.
        $parentTask = [];
        if (isset($input['parent_task_id']) && $input['parent_task_id']) {
            $parentTask = Task::findOrFail($input['parent_task_id']);

            if ($task->estimated_hours) {
                $parentTask->estimated_hours = $this->_getTasksEstimatehours([$parentTask->estimated_hours, $task->estimated_hours]);
                $parentTask->save();
            }

            if (isset($input['estimated_hours'])) {
                $parentTask->estimated_hours = $this->_getTasksEstimatehours([$parentTask->estimated_hours, $input['estimated_hours']], true);
                $parentTask->save();
            }
        }

        if ($input['status'] == 6) {
            $input['progress'] = 100;
        }

        //  Edit task user activity
        if ($task->fill($input)->save()) {
            // --
            // Update project progress.
            $this->_updateProjectProgress($task);
            // --
            // Save custom field.
            if (isset($input['custom_fields'])) {
                $this->helperRepo->saveCustomField(2, $id, $input['custom_fields']);
            }
            // --
            // Add activities
            createUserActivity(
                Task::MODULE_NAME,
                $id,
                $request->method(),
                $input['name'],
                $request->ip(),
                $id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Task Updated Successfully', $task, 'Task');

            // --
            // Sync user.
            $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();

            $userIds = [];
            $userIds = $project->users()->wherePivot('edit', 1)->pluck('id')->toArray();

            $userIds = array_merge($userIds, $super_admin_ids);
            array_push($userIds, $user->id); // login user
            if ($project->client_id) {
                array_push($userIds, $project->client_id); // client
            }
            $userIds = array_unique($userIds);

            if ($task->users()->sync($userIds)) {
                // --
                // Send mail.
                if ($input['assign_to']) {

                    // Assign user and super admins.
                    // $mailUserId = $super_admin_ids;
                    // array_push($mailUserId, $input['assign_to']);
                    // $mailUserId = array_unique($mailUserId);

                    // All project members.
                    if ($project->client_id) {
                        $userIds = array_diff($userIds, [$project->client_id]); // Unset client
                    }

                    $userData = User::select('id', 'email', DB::raw("CONCAT(firstname,' ',lastname) as name"))
                        ->whereIn('id', $userIds)
                        ->where('is_active', 1)
                        ->get();

                    if (!empty($userData)) {
                        // --
                        // Send email
                        $this->emailRepo->sendTaskUpdateMails(
                            $userData,
                            $task
                        );
                    }

                    $this->_sendTaskStartReminder($task);

                    // Add Notification.
                    createNotification([$task->assignUser], 'tasks', $task->id, 'Task Updated.', "#" . $task->generated_id . ' - ' . $task->name);

                } else {
                    // update
                }
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Send task start reminder.
     *
     * @param Object $task [Task object]
     *
     * @return void
     */
    private function _sendTaskStartReminder($task)
    {
        if ($task->task_start_date) {
            $setting = Setting::select('reminder_alert_before_days')->first();
            $beforeDays = explode(',', $setting->reminder_alert_before_days);

            foreach ($beforeDays as $days) {
                $reminderDate = Carbon::now()->addDays($days)->format('Y-m-d');
                if ($task->task_start_date->format('Y-m-d') == $reminderDate) {
                    $this->emailRepo->sendTaskReminderEmails($task);
                }
            }
        }
    }

    /**
     * Update status for specified resource.
     *
     * @param Request $request [Request for change task status]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function changeStatus($request, $id)
    {
        $input = $request->all();
        $task = Task::with([
            'users' => function ($query) {
                $query->where('is_active', true);
            },
        ])->findOrFail($id);
        $task->status = $input['status'];
        if ($input['status'] == 6) {
            $task->progress = 100;
        }
        if ($task->save()) {
            // --
            // Update project progress.
            $this->_updateProjectProgress($task);
            // --
            // Add activities
            createUserActivity(
                Task::MODULE_NAME,
                $task->id,
                $request->method(),
                $task->name,
                $request->ip(),
                $task->id,
                true
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Task Status Changed Successfully', $task, 'Task');

            // --
            // Send email
            $this->emailRepo->sendTaskStatusChangeMails($task);

            // Add Notification.
            createNotification([$task->assignUser], 'tasks', $task->id, 'Task Status Changed.', "#" . $task->generated_id . ' - ' . $task->name);

            return true;
        }
        return false;
    }

    /**
     * Changed task priority.
     *
     * @param Request $request [Request for change task priority]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeTaskPriority($request, $id)
    {
        $input['priority'] = $request->get('priority');
        $task = Task::findOrFail($id);
        $user = Auth::user();
        if ($task) {
            $task->fill($input)->save();
            // --
            // Add activity
            createUserActivity(
                Task::MODULE_NAME,
                $task->id,
                $request->method(),
                $task->name,
                $request->ip(),
                $task->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Task Priority Changed Successfully', $task, 'Task');
            
            // Add Notification.
            createNotification([$task->assignUser], 'tasks', $task->id, 'Task Priority Updated.', "#" . $task->generated_id . ' - ' . $task->name);
            
            return true;
        }
        return false;
    }

    /**
     * Update the project progress in storage.
     *
     * @param Object $task [Task object]
     *
     * @return Void
     */
    public function _updateProjectProgress($task)
    {
        if ($task->parent_task_id == 0 && $task->status == 6) {
            $project = Project::where('project_hours', 1)->find($task->project_id);

            if ($project) {
                $totalTask = Task::where('project_id', $task->project_id)->count();
                $completedTask = Task::where('project_id', $task->project_id)->where('status', 6)->count();
                $progress = ($completedTask * 100) / $totalTask;
                $project->fill(['progress' => $progress])->save();
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update task notes]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function taskNotesUpdate($request, $id)
    {
        $input = $request->all();
        $task = Task::findOrFail($id);

        if ($task->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                Task::MODULE_NAME,
                $task->id,
                $request->method(),
                $task->name,
                $request->ip(),
                $task->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Task Updated Successfully', $task, 'Task');

            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource
     *
     * @param Request $request [Request for delete task]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $task = Task::findOrFail($id);
        // --
        // Delete subtask hours from parent task hours.
        if ($task->parent_task_id != 0) {
            $parentTask = Task::find($task->parent_task_id);
            $parentTask->estimated_hours = $this->_getTasksEstimatehours([$parentTask->estimated_hours, $task->estimated_hours]);
            $parentTask->save();
        }
        $task->users()->detach();

        if ($task) {
            $task->delete();
            Timesheet::where('module_id', 2)->where('module_related_id', $id)->delete();
            // --
            // Add activities
            createUserActivity(
                Task::MODULE_NAME,
                $task->id,
                $request->method(),
                $task->name,
                $request->ip(),
                $task->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Task Deleted Successfully', $task, 'Task');

            // Add Notification.
            createNotification([$task->assignUser], 'tasks', $task->id, 'Task Deleted.', "#" . $task->generated_id . ' - ' . $task->name, $request->method());
        }
        // --
        // Remove subtask if parent task is deleted also detach users.
        $subTask = Task::where('parent_task_id', $id);
        foreach ($subTask->pluck('id') as $id) {
            $task = Task::find($id);
            $task->users()->detach();
        }

        if ($subTask->delete()) {
            return true;
        }
        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get task by project]
     *
     * @return Array
     */
    public function getTaskForTaskBoard($request)
    {
        $user = Auth::user();
        $task_table = config('core.acl.task_table');

        if ($request->has('filter') && $request->get('filter') === "my") {
            $tasks = Task::where(function ($query) use ($task_table, $user) {
                $query->where($task_table . '.assign_to', $user->id)
                    ->orWhere($task_table . '.created_by', $user->id);
            });
        } else {
            $tasks = $user->tasks();
        }

        $tasks = $tasks->with([
            'assignUser' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname',
                    'avatar'
                );
            },
            'createdUser' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname',
                    'avatar'
                );
            },
            'project1.users' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname'
                );
            },
            'users' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname',
                    'avatar'
                );
            },
        ]);

        if ($request->has('project_id')) {
            $tasks->where($task_table . '.project_id', $request->input('project_id'));
        }

        return $tasks->orderBy($task_table . '.status', 'asc')
            ->orderBy($task_table . '.order', 'asc')
            ->get();
    }

    /**
     * Update the specified resource
     *
     * @param Request $request [Request for update task status]
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
                        $task = Task::findOrFail($Value1);
                        $task->status = $value['status'];
                        $task->order = $order;
                        $task->save();
                        $order++;
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Store a newly imported resource in storage.
     *
     * @param Request $request [Request for import task]
     * @param Array   $csvData [Task Data]
     *
     * @return Boolean
     */
    public function createImportTask($request, $csvData)
    {
        $success = 0;
        $unsuccess = 0;
        $skip = 0;
        $result = [];
        $status = [
            1 => 'open',
            2 => 'in progress',
            3 => 'on hold',
            4 => 'waiting',
            5 => 'cancel',
            6 => 'completed',
        ];
        $priority = [
            1 => 'urgent',
            2 => 'very high',
            3 => 'high',
            4 => 'medium',
            5 => 'low',
        ];
        unset($csvData[0]); //Unset header.
        foreach ($csvData as $key => $value) {

            $date['plannedStartDate'] = null;
            $date['plannedEndDate'] = null;
            $date['taskStartDate'] = null;
            $date['taskEndDate'] = null;

            if (isset($value[3]) && !empty(trim($value[3]))) {
                $date['plannedStartDate'] = date("Y-m-d", strtotime(trim($value[3])));
            }
            if (isset($value[4]) && !empty(trim($value[4]))) {
                $date['plannedEndDate'] = date("Y-m-d", strtotime(trim($value[4])));
            }
            if (isset($value[5]) && !empty(trim($value[5]))) {
                $date['taskStartDate'] = date("Y-m-d", strtotime(trim($value[5])));
            }
            if (isset($value[6]) && !empty(trim($value[6]))) {
                $date['taskEndDate'] = date("Y-m-d", strtotime(trim($value[6])));
            }

            $value[8] = strtolower(trim(@$value[8]));
            $value[11] = strtolower(trim(@$value[11]));

            $project = Project::with(['users' => function ($query) {
                $query->select('id', 'email');
            },
            ])->where('project_name', trim(@$value[1]))->first();

            $validationError[$key] = $this->_validateTask(
                $value,
                $date,
                $status,
                $priority,
                $project
            );
            if (empty($validationError[$key])) {

                $loggin_user = User::select('id', 'firstname', 'lastname')
                    ->where('email', trim($value[10]))
                    ->first();

                $task = new Task;
                $task->generated_id = $this->getGeneratedId();
                $task->name = trim($value[0]);
                $task->project_id = $project->id;
                $task->project_version = empty(trim($value[2])) ? null : trim($value[2]);
                $task->planned_start_date = $date['plannedStartDate'];
                $task->planned_end_date = $date['plannedEndDate'];
                $task->task_start_date = $date['taskStartDate'];
                $task->task_end_date = $date['taskEndDate'];
                $task->estimated_hours = empty(trim($value[7])) ? '' : trim($value[7]);
                $task->status = empty(trim($value[8])) ? 1 : array_search($value[8], $status);
                $task->assign_to = empty(trim($value[9])) ? null : User::where('email', trim($value[9]))->first()->id;
                $task->created_by = $loggin_user->id;
                $task->priority = empty(trim($value[11])) ? 4 : array_search($value[11], $priority);
                $task->progress = empty(trim($value[12])) ? 0 : trim($value[12]);
                $task->description = trim($value[13]);
                $task->order = Task::count() + 1;

                // --
                // Create task
                if ($task->save()) {
                    $userIds = [];
                    if ($project->assign_to) {
                        $team = Team::with(['members' => function ($query) {
                            $query->select(config('core.acl.users_table') . '.id');
                        },
                        ])
                            ->find($project->assign_to);
                        foreach ($team->members as $key2 => $value) {
                            $userIds[] = $value->id;
                        }
                    }

                    $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();
                    $userIds = array_merge($userIds, $super_admin_ids);
                    array_push($userIds, $task->created_by);
                    if ($project->client_id) {
                        array_push($userIds, $project->client_id); // client
                    }
                    $userIds = array_unique($userIds);

                    if ($task->users()->sync($userIds)) {
                        // --
                        // Add activities
                        createUserActivity(
                            Task::MODULE_NAME,
                            $task->id,
                            $request->method(),
                            $task->name,
                            $request->ip(),
                            $task->id
                        );
                        // --
                        // Send mail.
                        $mailUserId = $super_admin_ids;
                        array_push($mailUserId, $task->assign_to);
                        $mailUserId = array_unique($mailUserId);

                        if (!empty($mailUserId)) {
                            $this->_sendMailEveryone($mailUserId, $task);
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
            "skip" => intval($skip),
            "total" => intval($unsuccess) + intval($success) + intval($skip),
        );
    }

    /**
     * Validate imported task.
     *
     * @param Array  $data     [Row data]
     * @param Array  $date     [Date array]
     * @param Array  $status   [Status array]
     * @param Array  $priority [Priority array]
     * @param Object $project  [Project object]
     *
     * @return Array
     */
    private function _validateTask($data, $date, $status, $priority, $project)
    {
        $error = array();

        if (empty($data[0])) {
            $error[] = 'Task name is required.';
        } elseif (strlen($data[0]) > config('core.max_length')) {
            $error[] = 'The task name may not be greater than ' . config('core.max_length') . ' characters.';
        }

        if (empty($data[1])) {
            $error[] = 'Project name is required.';
        } else {
            if (!$project) {
                $error[] = 'Project is not exists.';
            } else {

                // --
                // Project version
                if (!empty($data[2])) {
                    $project_version = explode(',', $project->project_version);
                    if (!in_array(trim($data[2]), $project_version)) {
                        $error[] = 'Please enter valid project version.';
                    }
                }

                // --
                // Assign to
                if (!empty($data[9])) {
                    if (!filter_var(trim($data[9]), FILTER_VALIDATE_EMAIL)) {
                        $error[] = "Assigned to is not a valid email address";
                    } else {
                        foreach ($project->users as $key => $value) {
                            $users[] = $value->email;
                        }
                        if (!in_array(trim($data[9]), $users)) {
                            $error[] = 'Assigned to is not a project team member.';
                        }
                    }
                }
            }
        }

        if (!empty($data[3])) {
            if (false === strtotime($data[3])) {
                $error[] = 'Please enter valid planned start date.';
            }
        }

        if (!empty($data[4])) {
            if (false === strtotime($data[4])) {
                $error[] = 'Please enter valid planned end date.';
            } elseif ($date['plannedEndDate'] < $date['plannedStartDate']) {
                $error[] = 'The planned end date must be a date after or equal to planned start date.';
            }
        }

        if (!empty($data[5])) {
            if (false === strtotime($data[5])) {
                $error[] = 'Please enter valid task start date.';
            }
        }

        if (!empty($data[6])) {
            if (false === strtotime($data[6])) {
                $error[] = 'Please enter valid task end date.';
            } elseif (!($date['taskEndDate'] >= $date['taskStartDate'])) {
                $error[] = 'The task end date must be a date after or equal to task start date.';
            }
        }

        if (!empty($data[7])) {
            if (preg_match('/^[0-9]+\:[0-5][0-9]$/', $data[7]) !== 1) {
                $error[] = 'The estimated hours allow only digits, 2 digits after colon(less than 60) without any special characters.';
            }
        }

        if (!empty($data[8]) && !in_array($data[8], $status)) {
            $error[] = 'Please enter valid status.';
        }

        if (empty($data[10])) {
            $error[] = 'Created By is required.';
        } elseif (!filter_var(trim($data[10]), FILTER_VALIDATE_EMAIL)) {
            $error[] = "Created by is not a valid email address";
        } else {
            $created_user = User::whereEmail(trim($data[10]))->exists();
            if (!$created_user) {
                $error[] = 'Please enter valid created by user email.';
            }
        }

        if (!empty($data[11]) && !in_array($data[11], $priority)) {
            $error[] = 'Please enter valid priority.';
        }

        return $error;
    }

    /**
     * Retrive last insert id
     *
     * @return id
     */
    public function getGeneratedId()
    {
        $task = Task::where('parent_task_id', 0)
            ->withTrashed()
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->first();

        if ($task) {
            return 'T' . str_pad($task->id + 1, 4, "0", STR_PAD_LEFT);
        }
        return 'T' . str_pad(1, 4, "0", STR_PAD_LEFT);
    }

    /**
     * Retrive count of subtask by parent task id.
     *
     * @param Int $parent_task_id [Parent task row id]
     *
     * @return count
     */
    public function getSubTaskCountByParent($parent_task_id)
    {
        $total = Task::where('parent_task_id', $parent_task_id)
            ->withTrashed()
            ->count();
        return str_pad($total + 1, 2, "0", STR_PAD_LEFT);
    }

    /**
     * Get tasks for reports
     *
     * @param Request $request [Request for get task]
     *
     * @return Response
     */
    public function getTaskForReport($request)
    {
        $task_table = config('core.acl.task_table');
        $project_table = config('core.acl.projects_table');
        $user_table = config('core.acl.users_table');
        $user = Auth::user();

        $columns = array(
            0 => $task_table . '.generated_id',
            1 => $task_table . '.name',
            2 => $task_table . '.planned_start_date',
            3 => $task_table . '.planned_end_date',
            4 => $task_table . '.task_start_date',
            5 => $task_table . '.task_end_date',
            6 => $task_table . '.actual_hours',
            7 => $task_table . '.progress',
            8 => DB::raw('concat(project_created.firstname," ",project_created.lastname)'),
            9 => DB::raw("CONCAT($user_table.firstname,' ',$user_table.lastname)"),
            10 => $task_table . '.priority',
            11 => $task_table . '.status',
            12 => $project_table . '.project_name',
            13 => $task_table . '.project_version',
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $columns_search = $request->input('columns');

        $task = $user->tasks()->select(
            $task_table . '.*',
            $project_table . '.project_name',
            'project_created.firstname as created_firstname',
            'project_created.lastname as created_lastname',
            'project_created.avatar as created_avatar',
            $user_table . '.firstname as assign_firstname',
            $user_table . '.lastname as assign_lastname',
            $user_table . '.avatar as assign_avatar'
        )
            ->join($project_table, $project_table . '.id', '=', $task_table . '.project_id')
            ->join($user_table . ' as project_created', 'project_created.id', '=', $task_table . '.created_by')
            ->leftjoin($user_table, $user_table . '.id', '=', $task_table . '.assign_to');

        $matchThese = [];
        foreach ((array) $columns_search as $key => $value) {
            if (!empty($value['search']['value'])) {
                array_push(
                    $matchThese,
                    [$columns[$key], 'LIKE', "%{$value['search']['value']}%"]
                );
            }
        }

        $totalData = $task->count();
        $totalFiltered = $totalData;

        if (!empty($matchThese)) {
            $task = $task->where($matchThese);
            $totalFiltered = $task->count();
        }

        $data = $task->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        return array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
    }

    /**
     * Task send mail.
     *
     * @param Array  $uniqueUserId  [User id]
     * @param Object $task          [Task id]
     *
     * @return Boolean
     */
    private function _sendMailEveryone($uniqueUserId, $task)
    {
        $userData = User::select('id', 'firstname', 'lastname', 'email')
            ->whereIn('id', $uniqueUserId)
            ->where('is_active', 1)
            ->get();

        if (!empty($userData)) {
            // Send email
            $this->emailRepo->sendTaskAssignUserEmail($userData, $task);
        }
        return true;
    }

    /**
     * Check user permission.
     *
     * @param Int $task_id [Task Id]
     *
     * @return Boolean
     */
    public function checkPermission($task_id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $taskUser = Task::where('id', $task_id)
            ->where(
                function ($q) {
                    $q->where('assign_to', Auth::user()->id)
                        ->orWhere('created_by', Auth::user()->id);
                }
            )
            ->first();

        if ($taskUser) {
            return true;
        }
        return false;
    }

    /**
     * Display a listing of the resource for calendar.
     *
     * @return Array
     */
    public function getTasksForCalendar()
    {
        $user = Auth::user();
        return Task::with([
            'assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
        ])
            ->where(function ($query) use ($user) {
                $query->where('assign_to', $user->id)->orWhere('created_by', $user->id);
            })
            ->whereNotIn('status', [5, 6])
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * Calculate estimated hours based on parents and sub tasks.
     *
     * @param array $times [Estiamted hours array]
     * @param boolean $isCreated [create or delete]
     *
     * @return String
     */
    private function _getTasksEstimatehours($times = [], $isCreated = false)
    {
        $sumSeconds = 0;
        if ($isCreated) {
            $sumSeconds = $this->timeToSeconds($times[0]) + $this->timeToSeconds($times[1]);
        } else {
            $sumSeconds = $this->timeToSeconds($times[0]) - $this->timeToSeconds($times[1]);
        }

        $hours = floor($sumSeconds / 3600);
        $minutes = floor(($sumSeconds % 3600) / 60);
        return $hours . ':' . $minutes;
    }

    /**
     * Convert time to seconds.
     *
     * @param array $time [Estiamted hour]
     *
     * @return number
     */
    private function timeToSeconds($time)
    {
        list($hours, $minutes) = explode(":", $time);

        return ($hours * 60 * 60) + ($minutes * 60);
    }

    /**
     * Convert project planner task to task.
     *
     * @param Request $request [Request for create task]
     *
     * @return Boolean
     */
    public function convertSprintTaskToTask($request)
    {
        $input = $request->all();
        $user = Auth::user();
        $project = Project::findOrFail($input['project_id']);
        $task = new Task;
        $input['generated_id'] = $this->getGeneratedId();
        $input['status'] = 1;
        $input['priority'] = 4;
        $input['created_by'] = $user->id;
        $input['order'] = Task::count() + 1;
        $input['planned_start_date'] = $input['start_date'];
        $input['planned_end_date'] = $input['end_date'];
        $tasks = $task->create($input);

        if ($tasks) {
            // --
            // Add activities
            createUserActivity(
                Task::MODULE_NAME,
                $tasks->id,
                $request->method(),
                $tasks->name,
                $request->ip(),
                $tasks->id
            );

            $super_admin_ids = User::where('is_super_admin', 1)->pluck('id')->toArray();

            $userIds = [];
            if ($project->assign_members != 'Unassign') {
                $userIds = explode(',', $project->assign_members);
            }

            $userIds = array_merge($userIds, $super_admin_ids);
            array_push($userIds, $user->id); // login user
            if ($project->client_id) {
                array_push($userIds, $project->client_id); // client
            }
            $userIds = array_unique($userIds);

            if ($tasks->users()->sync($userIds)) {
                // --
                // Send mail.
                if ($input['assign_to']) {
                    $mailUserId = $super_admin_ids;
                    array_push($mailUserId, $input['assign_to']);
                    $mailUserId = array_unique($mailUserId);
                    $this->_sendMailEveryone($mailUserId, $tasks);
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
