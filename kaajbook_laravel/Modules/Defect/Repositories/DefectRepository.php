<?php
namespace Modules\Defect\Repositories;

use Auth;
use Carbon\Carbon;
use DB;
use Modules\CustomField\Models\CustomField;
use Modules\Defect\Models\Defect;
use Modules\Defect\Models\DefectHistory;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\Helper\Repositories\HelperRepository;
use Modules\Projects\Models\Project;
use Modules\Setting\Models\Setting;
use Modules\Slack\Repositories\SlackRepository;
use Modules\Team\Models\Team;
use Modules\Timesheet\Models\Timesheet;
use Modules\UserActivity\Models\UserActivity;
use Modules\User\Models\User\User;

/**
 * Class DefectRepository
 *
 * Defect create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Defect
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DefectRepository
{
    protected $helperRepo;
    protected $emailsHelper;
    protected $uploadHelper;
    protected $slackRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param HelperRepository $helperRepo   [Object]
     * @param EmailsHelper     $emailsHelper [Object]
     * @param EmailsHelper     $uploadHelper [Object]
     *
     * @return void
     */
    public function __construct(
        HelperRepository $helperRepo,
        EmailsHelper $emailsHelper,
        UploadFileHelper $uploadHelper,
        SlackRepository $slackRepo
    ) {
        $this->helperRepo = $helperRepo;
        $this->emailsHelper = $emailsHelper;
        $this->uploadHelper = $uploadHelper;
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
        return $user->defects()
            ->select(
                'id',
                'defect_name',
                'create_user_id',
                'generated_id',
                'defect_type',
                'severity',
                'status',
                'assign_member',
                'project_id',
                'project_version',
                'created_at'
            )->with([
            'users' => function ($query) {
                $query->select('id', 'firstname', 'lastname');
            },
        ])
            ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get defect]
     *
     * @return Object
     */
    public function getAllDefects($request)
    {
        $defects_table = config('core.acl.defects_table');
        $user_table = config('core.acl.users_table');
        $user = Auth::user();

        $columns = array(
            0 => $defects_table . '.id',
            1 => $defects_table . '.defect_name',
            2 => $defects_table . '.start_date',
            3 => $defects_table . '.end_date',
            4 => $defects_table . '.actual_hours',
            5 => 'defect_assigned.firstname',
            6 => $defects_table . '.severity',
            7 => $defects_table . '.status',
        );

        if ($request->get('isUserProfile') && $request->has('user_id')) {
            $defect = Defect::where($defects_table . '.assign_member', $request->get('user_id'));
            $statusCount = [];

            $columns = array(
                0 => $defects_table . '.id',
                1 => $defects_table . '.defect_name',
                2 => $defects_table . '.start_date',
                3 => $defects_table . '.end_date',
                4 => $defects_table . '.defect_type',
                5 => $defects_table . '.severity',
                6 => $defects_table . '.status',
            );

        } else if ($request->has('filter') && $request->get('filter') === "selected") {
            $defect = Defect::where(function ($query) use ($defects_table, $user) {
                $query->where($defects_table . '.assign_member', $user->id)
                    ->orWhere($defects_table . '.create_user_id', $user->id);
            });
            $statusCount = $this->_getDefectCount(true);
        } else {
            $defect = $user->defects();
            $statusCount = $this->_getDefectCount();
        }

        $status = $request->get('status');
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $defect = $defect->select(
            $defects_table . '.*',
            'defect_assigned.firstname as assigned_firstname',
            'defect_assigned.lastname as assigned_lastname',
            'defect_assigned.avatar as assigned_avatar'
        )
            ->leftjoin(
                $user_table . ' as defect_assigned',
                'defect_assigned.id',
                '=',
                $defects_table . '.assign_member'
            );

        if ($status) {
            if ($status == 4) {
                $defect->whereIn($defects_table . '.status', [4, 6]);
            } elseif ($status == 5) {
                $defect->whereIn($defects_table . '.status', [2, 5, 7]);
            } elseif ($status == 8) {
                // Overdue
                $defect->whereIn($defects_table . '.status', [1, 3, 4, 6])
                    ->whereDate($defects_table . '.end_date', '<', Carbon::now());
            } else {
                $defect->whereIn($defects_table . '.status', [$status]);
            }
        }

        $totalData = $defect->count();
        $totalFiltered = $totalData;

        // --
        // Search
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $defect = $defect->where(
                function ($query) use ($search, $defects_table) {
                    $query->where($defects_table . '.defect_name', 'LIKE', "%{$search}%")
                        ->orWhere($defects_table . '.generated_id', 'LIKE', "%{$search}%")
                        ->orWhere($defects_table . '.start_date', 'LIKE', "%{$search}%")
                        ->orWhere($defects_table . '.end_date', 'LIKE', "%{$search}%")
                        ->orWhere($defects_table . '.actual_hours', 'LIKE', "%{$search}%")
                        ->orWhere($defects_table . '.severity', 'LIKE', "%{$search}%")
                        ->orWhere(
                            DB::raw('concat(defect_assigned.firstname," ",defect_assigned.lastname)'),
                            'LIKE',
                            "%{$search}%"
                        )
                        ->orWhere($defects_table . '.status', 'LIKE', "%{$search}%")
                        ->orWhere($defects_table . '.project_version', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $defect->count();
        }

        $data = $defect->offset($start)
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
     * Get status type count.
     *
     ** @param Boolean     $isMy      [For all defect or user defect]
     *
     * @return array
     */
    private function _getDefectCount($isMy = false)
    {
        $user = Auth::user();
        $result['all'] = $this->_getStatusWiseCount([1, 2, 3, 4, 5, 6, 7], $user, $isMy);
        if ($result['all'] > 0) {
            $result['assigned'] = $this->_getStatusWiseCount([1], $user, $isMy);
            $result['closed'] = $this->_getStatusWiseCount([2], $user, $isMy);
            $result['in_progress'] = $this->_getStatusWiseCount([3], $user, $isMy);
            $result['open'] = $this->_getStatusWiseCount([4], $user, $isMy);
            $result['solved'] = $this->_getStatusWiseCount([5], $user, $isMy);
            $result['re_open'] = $this->_getStatusWiseCount([6], $user, $isMy);
            $result['deferred'] = $this->_getStatusWiseCount([7], $user, $isMy);
            $result['overdue'] = $this->_getOverdueCount($user, $isMy);
        }
        return $result;
    }

    /**
     * Defect status wise counting.
     *
     * @param Int    $status [Status id]
     * @param Object $user   [User object]
     * @param Boolean $isMy  [For all defect or user defect]
     *
     * @return Count
     */
    private function _getStatusWiseCount($status, $user, $isMy)
    {
        if ($isMy) {
            return Defect::where(function ($query) use ($user) {
                $query->where('assign_member', $user->id)
                    ->orWhere('create_user_id', $user->id);
            })
                ->whereIn('status', $status)
                ->count();
        } else {
            return $user->defects()
                ->whereIn('status', $status)
                ->count();
        }
    }

    /**
     * Defect overdue counting.
     *
     * @param Object $user   [User object]
     * @param Boolean $isMy  [For all defect or user defect]
     *
     * @return Count
     */
    private function _getOverdueCount($user, $isMy)
    {
        if ($isMy) {
            $overdueDefect = Defect::where(function ($query) use ($user) {
                $query->where('assign_member', $user->id)
                    ->orWhere('create_user_id', $user->id);
            });
        } else {
            $overdueDefect = $user->defects();
        }

        return $overdueDefect->whereIn('status', [1, 3, 4, 6])
            ->whereDate('end_date', '<', Carbon::now())
            ->count();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get defect by project]
     *
     * @return Array
     */
    public function getDefectForKanban($request)
    {
        $user = Auth::user();
        $defects_table = config('core.acl.defects_table');

        if ($request->has('filter') && $request->get('filter') === "my") {
            $defect = Defect::where(function ($query) use ($defects_table, $user) {
                $query->where($defects_table . '.assign_member', $user->id)
                    ->orWhere($defects_table . '.create_user_id', $user->id);
            });
        } else {
            $defect = $user->defects();
        }

        $defect = $defect->with([
            'assignUser' => function ($query) {
                $query->select(
                    'id',
                    'firstname',
                    'lastname',
                    'avatar'
                );
            }]);

        if ($request->has('project_id')) {
            $defect->where($defects_table . '.project_id', $request->input('project_id'));
        }

        return $defect->orderBy($defects_table . '.status', 'asc')
            ->orderBy($defects_table . '.orders', 'asc')
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
        $defect = Defect::with([
            'project' => function ($query) {
                $query->select('id', 'project_name', 'project_version', 'start_date', 'end_date');
            },
            'createdUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'comments.user' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
            'attachments',
            'history' => function ($query) {
                $query->orderBy('id', 'desc');
            },
            'users' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
        ])
            ->where('id', $id)
            ->first();

        if ($defect) {
            $defect['custom_fields'] = CustomField::getViewFields(3)->get();
            $defect['activities'] = UserActivity::getActivities(['Defect', 'DefectComment', 'DefectAttachment'], $id)->get();
            return $defect;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create defect]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $defect = new Defect;
        $project = Project::findOrFail($input['project_id']);
        $user = Auth::user();
        $input['generated_id'] = $this->getGeneratedId();
        $input['create_user_id'] = $user->id;
        $input['orders'] = Defect::count();
        $userIds = [];

        if ($input['assigned_group_id']) {
            $teamData = Team::with([
                'members' => function ($query) {
                    $query->select(config('core.acl.users_table') . '.id');
                },
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
        if ($project->client_id) {
            array_push($userIds, $project->client_id); // client
        }
        $uniqueUserId = array_unique($userIds);

        // --
        // Upload attachment
        if (isset($input["file"]) && $input["file"]) {
            $defect->attachment_hash = $this->uploadHelper->uploadAttachment($input, 'defect');
        }

        $defect->fill($input);
        if ($defect->save()) {
            $defect->users()->sync($uniqueUserId);
            // --
            // Save custom field
            if (isset($input['custom_fields'])) {
                $this->helperRepo->saveCustomField(3, $defect->id, $input['custom_fields']);
            }

            // --
            // Add activity
            createUserActivity(
                Defect::MODULE_NAME,
                $defect->id,
                $request->method(),
                $input['defect_name'],
                $request->ip(),
                $defect->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Defect Created Successfully', $defect, 'Defect');

            // --
            // Add defect history
            $history = $this->_createHistory(
                $input['defect_name'],
                $user->id,
                'create',
                $user->id,
                $input['status']
            );
            $defect->history()->save($history);
            // --
            // Send email
            if ($input['assign_member']) {

                // Assign user and Super admins.
                // $mailUserId = $super_admin_ids;
                // array_push($mailUserId, $input['assign_member']);
                // $mailUserId = array_unique($mailUserId);

                // All team member.
                if ($project->client_id) {
                    $uniqueUserId = array_diff($uniqueUserId, [$project->client_id]); // Unset client
                }

                $this->_sendMailEveryone(
                    $uniqueUserId,
                    $defect
                );

                $this->_sendDefectStartReminder($defect);

                // Add Notification.
                createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Created.',  "#" . $defect->generated_id . ' - ' . $defect->defect_name);

            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update defect]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $defect = Defect::find($id);
        $oldDefect = $defect->toArray();
        $project = Project::findOrFail($defect->project_id);
        $user = Auth::user();
        $userIds = [];

        // --
        // Upload attachment
        if (isset($input["file"]) && $input["file"]) {
            //
            // Delete old attachment.
            if ($defect->attachment_hash) {
                $this->_deleteFile($defect->attachment_hash);
            }
            $defect->attachment_hash = $this->uploadHelper->uploadAttachment($input, 'defect');
        }

        if ($input['assigned_group_id']) {
            $teamData = Team::with([
                'members' => function ($query) {
                    $query->select(config('core.acl.users_table') . '.id');
                },
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
        if ($project->client_id) {
            array_push($userIds, $project->client_id); // client
        }
        $uniqueUserId = array_unique($userIds);

        if ($defect->fill($input)->save()) {
            $defect->users()->sync($uniqueUserId);
            // --
            // Save custom field
            if (isset($input['custom_fields'])) {
                $this->helperRepo->saveCustomField(3, $id, $input['custom_fields']);
            }

            // --
            // Add activity
            createUserActivity(
                Defect::MODULE_NAME,
                $defect->id,
                $request->method(),
                $input['defect_name'],
                $request->ip(),
                $defect->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Defect Updated Successfully', $defect, 'Defect');

            // --
            // Add defect history
            $history = $this->_createHistory(
                $input['defect_name'],
                $user->id,
                'edit',
                $defect->create_user_id,
                $defect->status
            );
            $defect->history()->save($history);
            // --
            // Send email
            if ($input['assign_member']) {

                // Assign user and Super admins.
                // $mailUserId = $super_admin_ids;
                // array_push($mailUserId, $input['assign_member']);
                // $mailUserId = array_unique($mailUserId);

                // All team member.
                if ($project->client_id) {
                    $uniqueUserId = array_diff($uniqueUserId, [$project->client_id]); // Unset client
                }

                $userData = User::select('id', 'email', DB::raw("CONCAT(firstname,' ',lastname) as name"))
                    ->whereIn('id', $uniqueUserId)
                    ->where('is_active', 1)
                    ->get();

                // --
                // Send email
                $this->emailsHelper->sendDefectUpdateMails(
                    $userData,
                    $defect
                );

                $this->_sendDefectStartReminder($defect);

                // Add Notification.
                createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Updated.',  "#" . $defect->generated_id . ' - ' . $defect->defect_name);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Send defect start reminder.
     *
     * @param Object $defect [Defect object]
     *
     * @return void
     */
    private function _sendDefectStartReminder($defect)
    {
        if ($defect->start_date) {
            $setting = Setting::select('reminder_alert_before_days')->first();
            $beforeDays = explode(',', $setting->reminder_alert_before_days);

            foreach ($beforeDays as $days) {
                $reminderDate = Carbon::now()->addDays($days)->format('Y-m-d');
                if ($defect->start_date->format('Y-m-d') == $reminderDate) {
                    $this->emailsHelper->sendDefectReminderEmails($defect);
                }
            }
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update defect notes]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function defectNotesUpdate($request, $id)
    {
        $input = $request->all();
        $defect = Defect::findOrFail($id);
        $user = Auth::user();

        if ($defect->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                Defect::MODULE_NAME,
                $defect->id,
                $request->method(),
                $defect->defect_name,
                $request->ip(),
                $defect->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Defect Updated Successfully', $defect, 'Defect');

            // --
            // Add defect history
            $history = $this->_createHistory(
                $defect->defect_name,
                $user->id,
                'edit',
                $defect->create_user_id,
                $defect->status
            );
            $defect->history()->save($history);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Changed defect status.
     *
     * @param Request $request [Request for change defect status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeDefectStatus($request, $id)
    {
        $input['status'] = $request->get('status');
        $defect = Defect::with([
            'users' => function ($query) {
                $query->where('is_active', true);
            },
        ])->findOrFail($id);
        $user = Auth::user();
        if ($defect) {
            $defect->fill($input)->save();
            // --
            // Add defect history
            $history = $this->_createHistory(
                $defect->defect_name,
                $user->id,
                'status',
                $defect->create_user_id,
                $input['status']
            );
            $defect->history()->save($history);

            // --
            // Add activity
            createUserActivity(
                Defect::MODULE_NAME,
                $defect->id,
                $request->method(),
                $defect->defect_name,
                $request->ip(),
                $defect->id,
                true
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Defect Status Changed Successfully', $defect, 'Defect');

            // --
            // Send email
            $this->emailsHelper->sendDefectStatusChangeMails($defect);

            // Add Notification.
            createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Status Changed.',  "#" . $defect->generated_id . ' - ' . $defect->defect_name);

            return true;
        }
        return false;
    }

    /**
     * Changed defect severity.
     *
     * @param Request $request [Request for change defect severity]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeDefectSeverity($request, $id)
    {
        $input['severity'] = $request->get('severity');
        $defect = Defect::findOrFail($id);
        $user = Auth::user();
        if ($defect) {
            $defect->fill($input)->save();
            // --
            // Add defect history
            $history = $this->_createHistory(
                $defect->defect_name,
                $user->id,
                'severity',
                $defect->create_user_id,
                $defect->status
            );
            $defect->history()->save($history);

            // --
            // Add activity
            createUserActivity(
                Defect::MODULE_NAME,
                $defect->id,
                $request->method(),
                $defect->defect_name,
                $request->ip(),
                $defect->id
            );

            // --
            // Post message to slack
            $this->slackRepo->postActivitiesMessage('Defect Severity Changed Successfully', $defect, 'Defect');

            // Add Notification.
            createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Severity Updated.',  "#" . $defect->generated_id . ' - ' . $defect->defect_name);
           
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete defect]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $defect = Defect::findOrFail($id);
        if ($defect) {
            $defect->users()->detach();
            if ($defect->attachment_hash) {
                $this->_deleteFile($defect->attachment_hash);
            }
            if ($defect->delete()) {
                Timesheet::where('module_id', 3)->where('module_related_id', $id)->delete();
                // --
                // Add activity
                createUserActivity(
                    Defect::MODULE_NAME,
                    $defect->id,
                    $request->method(),
                    $defect->defect_name,
                    $request->ip(),
                    $defect->id
                );

                // --
                // Post message to slack
                $this->slackRepo->postActivitiesMessage('Defect Deleted Successfully', $defect, 'Defect');

                // --
                // Add defect history
                $history = $this->_createHistory(
                    $defect->defect_name,
                    auth()->user()->id,
                    'delete',
                    $defect->create_user_id,
                    $defect->status
                );
                $defect->history()->save($history);

                // Add Notification.
                createNotification([$defect->assignUser], 'defects', $defect->id, 'Defect Deleted.',  "#" . $defect->generated_id . ' - ' . $defect->defect_name, $request->method());
                return true;
            }
        }
        return false;
    }

    /**
     * Delete attachment file.
     *
     * @param String $fileName [File name]
     *
     * @return Boolean
     */
    private function _deleteFile($fileName)
    {
        $file = public_path() . '/uploads/defect/' . $fileName;
        if (file_exists($file)) {
            unlink($file);
        }
        return true;
    }

    /**
     * Defect send mail.
     *
     * @param Array  $uniqueUserId  [User id]
     * @param Object $defect        [Defect]
     *
     * @return Boolean
     */
    private function _sendMailEveryone($uniqueUserId, $defect)
    {
        $userData = User::select('id', 'firstname', 'lastname', 'email')
            ->whereIn('id', $uniqueUserId)
            ->where('is_active', 1)
            ->get();

        if (!empty($userData)) {
            // --
            // Send email
            $this->emailsHelper->sendDefectAssignMails(
                $userData,
                $defect
            );
        }
        return true;
    }

    /**
     * Defect history save.
     *
     * @param String $defectName   [Defect name]
     * @param Int    $loginUserId  [Login user id]
     * @param String $method       [Request method]
     * @param Int    $createUserId [Created user id]
     * @param String $statusType   [Status type]
     *
     * @return object
     */
    private function _createHistory($defectName, $loginUserId, $method, $createUserId, $statusType = 0)
    {
        $defectHistory = new DefectHistory;
        $defectHistory->created_by_id = $createUserId;
        if ($method == 'create') {
            $defectHistory->description = "Defect information added : " . '<b>' . $defectName . '</b>';
        } elseif ($method == 'edit') {
            $defectHistory->description = "Defect information updated : " . '<b>' . $defectName . '</b>';
            $defectHistory->updated_by_id = $loginUserId;
        } elseif ($method == 'status') {
            $defectHistory->description = "Defect status changed : " . '<b>' . $defectName . '</b>';
            $defectHistory->updated_by_id = $loginUserId;
        } elseif ($method == 'severity') {
            $defectHistory->description = "Defect severity changed : " . '<b>' . $defectName . '</b>';
            $defectHistory->updated_by_id = $loginUserId;
        } else {
            $defectHistory->description = "Defect information deleted : " . '<b>' . $defectName . '</b>';
            $defectHistory->updated_by_id = $loginUserId;
        }

        if ($statusType == 2) {
            $defectHistory->closed_by_id = $loginUserId;
        }
        if ($statusType == 5) {
            $defectHistory->solved_by_id = $loginUserId;
        }
        return $defectHistory;
    }

    /**
     * Retrive generated id.
     *
     * @return String
     */
    public function getGeneratedId()
    {
        return 'DEF' . str_pad(nextAutoID(config('core.acl.defects_table')), 4, "0", STR_PAD_LEFT);
    }

    /**
     * Display defect all data.
     *
     * @param Request $request [Request for get defect report]
     *
     * @return Response
     */
    public function getDefectForReport($request)
    {
        $defects_table = config('core.acl.defects_table');
        $project_table = config('core.acl.projects_table');
        $user_table = config('core.acl.users_table');
        $user = Auth::user();

        $columns = array(
            0 => $defects_table . '.id',
            1 => $defects_table . '.defect_name',
            2 => $defects_table . '.start_date',
            3 => $defects_table . '.end_date',
            // 4 => $defects_table . '.actual_hours',
            // 5 => DB::raw("CONCAT($user_table.firstname,' ',$user_table.lastname)"),
            4 => DB::raw("CONCAT(defect_assigned.firstname,' ',defect_assigned.lastname)"),
            // 7 => $defects_table . '.defect_type',
            // 8 => $defects_table . '.severity',
            5 => $defects_table . '.status',
            6 => $project_table . '.project_name',
           // 11 => $defects_table . '.project_version',
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $columns_search = $request->input('columns');

        $defect = $user->defects()->select(
            $defects_table . '.*',
            DB::raw('CASE WHEN defect_type = 1 THEN "Defect" ELSE "Enhancement" END AS defect_types'),
            $project_table . '.project_name',
            $user_table . '.firstname as created_firstname',
            $user_table . '.lastname as created_lastname',
            $user_table . '.avatar as created_avatar',
            'defect_assigned.firstname as assigned_firstname',
            'defect_assigned.lastname as assigned_lastname',
            'defect_assigned.avatar as assigned_avatar'
        )
            ->join($project_table, $project_table . '.id', '=', $defects_table . '.project_id')
            ->join($user_table, $user_table . '.id', '=', $defects_table . '.create_user_id')
            ->leftjoin($user_table . ' as defect_assigned', 'defect_assigned.id', '=', $defects_table . '.assign_member');

        $matchThese = [];
        foreach ((array) $columns_search as $key => $value) {
            if (!empty($value['search']['value'])) {
                array_push(
                    $matchThese,
                    [$columns[$key], 'LIKE', "%{$value['search']['value']}%"]
                );
            }
        }

        $totalData = $defect->count();
        $totalFiltered = $totalData;

        if (!empty($matchThese)) {
            $defect = $defect->where($matchThese);
            $totalFiltered = $defect->count();
        }

        $data = $defect->offset($start)
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
     * Get defects for calendar.
     *
     * @param Request $request [Request for get defects]
     *
     * @return Response
     */
    public function getDefectForCalendar($request)
    {
        $user = Auth::user();
        return Defect::with([
            'assignUser' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            },
        ])
            ->where(function ($query) use ($user) {
                $query->where('assign_member', $user->id)
                    ->orWhere('create_user_id', $user->id);
            })
            ->whereNotIn('status', [2, 5, 7])
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * Check user permission.
     *
     * @param Int $defect_id [Defect Id]
     *
     * @return Boolean
     */
    public function checkPermission($defect_id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->is_super_admin) {
            return true;
        }

        $defectUser = Defect::where('id', $defect_id)
            ->where(function ($q) {
                $q->where('assign_member', Auth::user()->id)
                    ->orWhere('create_user_id', Auth::user()->id);
            }
            )
            ->first();

        if ($defectUser) {
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource
     *
     * @param Request $request [Request for update defect status and order]
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
                        $defect = Defect::findOrFail($Value1);
                        $defect->status = $value['status'];
                        $defect->orders = $order;
                        $defect->save();
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
