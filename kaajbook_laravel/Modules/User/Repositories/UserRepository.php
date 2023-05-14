<?php

namespace Modules\User\Repositories;

use Auth;
use Modules\DefectComment\Models\DefectComment;
use Modules\Helper\Helpers\CommonHelper;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\IncidentComment\Models\IncidentComment;
use Modules\KnowledgeBaseArticle\Models\KnowledgeBaseArticle;
use Modules\Mailbox\Models\Mailbox;
use Modules\Menu\Models\Menu;
use Modules\ProjectComment\Models\ProjectComment;
use Modules\Projects\Models\Project;
use Modules\TaskComment\Models\TaskComment;
use Modules\Timesheet\Models\Timesheet;
use Modules\UserActivity\Models\UserActivity;
use Modules\User\Http\Requests\ImportUserRequest;
use Modules\User\Models\Department\Department;
use Modules\User\Models\Department\DepartmentRoleMenu;
use Modules\User\Models\Department\DepartmentRoleUser;
use Modules\User\Models\Role\Role;
use Modules\User\Models\User\User;
use Validator;

/**
 * Class UserRepository
 *
 * User create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class UserRepository
{
    protected $emailRepo;
    protected $fileHelper;

    /**
     * Instantiate a new helper instance.
     *
     * @param EmailsHelper     $emailRepo  [Object]
     * @param UploadFileHelper $fileHelper [Object]
     *
     * @return void
     */
    public function __construct(
        EmailsHelper $emailRepo,
        UploadFileHelper $fileHelper, 
        CommonHelper $commonHelper
    ) {
        $this->emailRepo = $emailRepo;
        $this->fileHelper = $fileHelper;
        $this->commonHelper = $commonHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Array
     */
    public function getUsers()
    {
        return User::with(['departments', 'roles'])
            ->where('is_client', false)
            ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function getUserIdName()
    {
        return User::select('id', 'firstname', 'lastname', 'avatar')
            ->where('is_client', false)
            ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function getUserkeyBy()
    {
        return User::all('id', 'firstname', 'lastname', 'avatar')
            ->where('is_client', false)
            ->keyBy('id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Array
     */
    public function getMailUsers()
    {    
        $user = auth()->user();
        if ($user->hasRole('admin') || $user->can_view_all_users) {
            return User::select('id', 'firstname', 'lastname', 'email')
                ->whereNotIn('id', [$user->id])
                ->get();
        }else{
            $userIds = DepartmentRoleUser::whereIn('department_id', $user->departments->pluck('id'))->pluck('user_id')->toArray();
            $userIds = array_diff($userIds, [$user->id]); // Unset login user.
            return User::select('id', 'firstname', 'lastname', 'email')
                ->whereIn('id', $userIds)
                ->whereNotIn('id', [$user->id])
                ->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create user]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $user = $this->_createUserStub($request->all());
        if ($user->save()) {
            // --
            // Attach/Detach roles
            $this->_flushRoles($request->get('department_roles'), $user);

            // Send user creation notification to admins.
            $adminUsers = $this->getAdminUsers();
            $this->emailRepo->sendUserCreationEmailToAdmin($adminUsers, $user);

            // --
            // Add activities
            createUserActivity(
                User::MODULE_NAME,
                $user->id,
                $request->method(),
                $user->firstname.' '.$user->lastname,
                $request->ip()
            );
            return $user;
        } else {
            return false;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Int $id [Row id]
     *
     * @return Boolean
     */
    public function findById($id)
    {
        $user = User::with([
            'departments' => function ($q) {
                $q->distinct('id');
            }
        ])->withCount([
            'tasks' => function ($query) use ($id) {
                $query->where('assign_to', $id);
            },
            'defects' => function ($query) use ($id) {
                $query->where('assign_member', $id);
            },
            'incidents' => function ($query) use ($id) {
                $query->where('assign_to', $id);
            }
        ])->where('id', $id)->first();

        if (!empty($user)) {
            foreach ($user->departments as $department) {
                $department->roles = Department::where('id', $department->id)->with(
                    ['userRoles' => function ($query) use ($user) {
                        $query->wherePivot('user_id', $user->id);
                    }]
                )->first()->userRoles;
            }

            if ($user->is_client) {
                $user->projects_count = $user->projects()->whereNotIn('status', [4,5])->count();
            }else{
                $user->projects_count = $user->projects(true)->whereNotIn('status', [4,5])->count();
            }

            $project_comment = ProjectComment::select(\DB::raw('count(*) as projectComment'))
                ->where('user_id', $id)
                ->first()
                ->toArray();
            $task_comment = TaskComment::select(\DB::raw('count(*) as taskComment'))
                ->where('user_id', $id)
                ->first()
                ->toArray();
            $defect_comment = DefectComment::select(\DB::raw('count(*) as defectComment'))
                ->where('user_id', $id)
                ->first()
                ->toArray();
            $incident_comment = IncidentComment::select(\DB::raw('count(*) as incidentComment'))
                ->where('user_id', $id)
                ->first()
                ->toArray();
            $user->comment = $project_comment['projectComment'] + $task_comment['taskComment'] + $defect_comment['defectComment'] + $incident_comment['incidentComment'];

            $user->articles = KnowledgeBaseArticle::select(\DB::raw('count(*) as countArticle'))
                ->where('user_id', $id)
                ->first();
                
            $user->activities = UserActivity::select(\DB::raw('count(*) as countActivities'))
                ->where('user_id', $id)
                ->first();

            $user->mailbox = Mailbox::select(\DB::raw('count(*) as countMailbox'))->where('to', $user->email)->first();

            $timesheet = Timesheet::selectRaw('SUM(decimal_time) as total_decimal,module_id')
                ->where('created_user_id', $id)
                ->groupBy('module_id')
                ->get();

            $modules = \DB::Table(config('core.acl.modules_table'))->get();
            foreach ($modules as $key => $value) {
                $time[$value->module_id] = "00:00";
            }
            foreach ($timesheet as $key => $value) {
                $time[$value['module_id']] = $this->commonHelper->convertDecimalToTime($value['total_decimal']);
            }
            $time['totla_decimal_time'] = array_sum(array_column($timesheet->toArray(), 'total_decimal'));
            $time['total_hour_time'] = $this->commonHelper->convertDecimalToTime($time['totla_decimal_time']);
            $user->timesheet = $time;
        }

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update user]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $user = User::findOrFail($id);

        if (!empty($user)) {
            $input['avatar'] = $this->fileHelper->uploadImage(
                'user_avtar',
                $input['avatar']
            );
            
            if (!empty($user->avatar) && ($user->avatar != $input['avatar']) && !in_array($user->avatar, $input['UserAvatars'])) {
                $this->fileHelper->deleteFile('profile', $user->avatar);
            }

            if (isset($input['assign_permission'])) {
                $input['permission'] = $this->manageUserPermission($input);
            }
            
            $user->fill($input);

            // --
            // Save user
            if ($user->save()) {
                // --
                // Attach/Detach roles
                if ($request->has('department_roles')) {
                    $roles = $request->get('department_roles');
                    $this->_flushRoles($roles, $user);
                }
                // --
                // Add activities
                createUserActivity(
                    User::MODULE_NAME,
                    $user->id,
                    $request->method(),
                    $user->firstname.' '.$user->lastname,
                    $request->ip()
                );
                return $user;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    /**
     * Get user generated id.
     *
     * @return id
     */
    public function getUserGeneratedId()
    {
        return 'USR'.str_pad(nextAutoID(config('core.acl.users_table')), 4, "0", STR_PAD_LEFT);
    }

    /**
     * Create user stub.
     *
     * @param Aarray $input [Create user stub]
     *
     * @return Object
     */
    private function _createUserStub($input)
    {
        $user = new User;
        $user->user_generated_id = $input['user_generated_id'];
        $user->emp_id = strtoupper($input['emp_id']);
        $user->firstname = ucwords($input['firstname']);
        $user->lastname = ucwords($input['lastname']);
        $user->username = $input['username'];
        $user->email = $input['email'];
        $user->password = $input['password'];
        $user->country = isset($input['country']) ? $input['country'] : null;
        $user->language = isset($input['language']) ? $input['language'] : 'en';
        $user->skype = isset($input['skype']) ? $input['skype'] : null;
        $user->mobile = isset($input['mobile']) ? $input['mobile'] : null;
        $user->phone = isset($input['phone']) ? $input['phone'] : null;
        $user->address = isset($input['address']) ? $input['address'] : null;
        $user->is_super_admin = isset($input['is_super_admin']) ? $input['is_super_admin'] : 0;
        $user->can_view_all_users = isset($input['can_view_all_users']) ? $input['can_view_all_users'] : 0;
        $user->primary_manager = $input['primary_manager'];
        $user->secondary_manager = $input['secondary_manager'];
        $user->email_verification_code = str_random(40);
        if (isset($input['avatar'])) {
            $user->avatar = $this->fileHelper->uploadImage(
                'user_avtar',
                $input['avatar']
            );
        }
        $user->permission = $this->manageUserPermission($input);

        return $user;
    }

    /**
     * Manage user permissions
     *
     * @param Aarray $input [Input parameters]
     *
     * @return Response
     */
    public function manageUserPermission($input)
    {
        $permission = [];
        if ($input['assign_permission'] == 'all') {
            return 'all';
        } else {
            if (isset($input['permission'])) {
                foreach ($input['permission'] as $key => $value) {
                    if (!empty($value)) {
                        $permission[$key] = $value;
                    }
                }
                return json_encode($permission);
            }
        }
    }

    /**
     * Flush roles
     *
     * @param Array  $roles [Roles array]
     * @param Object $user  [User object]
     *
     * @return Boolen
     */
    private function _flushRoles($roles, $user)
    {
        $departmentRoleUsers = DepartmentRoleUser::where('user_id', $user->id)->get();
        if (!empty($departmentRoleUsers)) {
            DepartmentRoleUser::where('user_id', $user->id)->delete();
        }
        foreach ($roles as $key => $value) {
            $departmentRoleUsers = new DepartmentRoleUser();
            $departmentRoleUsers->department_id = $value['department_id'];
            $departmentRoleUsers->role_id = $value['role_id'];
            $departmentRoleUsers->user_id = $user->id;
            $departmentRoleUsers->save();
        }
        return true;
    }

    /**
     * Get user permissions.
     *
     * @return Object
     */
    public function getUserPermissions()
    {
        $department_role_menu_table = config('core.acl.department_role_menu_table');
        $user_role_department = config('core.acl.user_role_department');
        $user_table = config('core.acl.users_table');

        // --
        // Get permission users
        return User::join($user_role_department, $user_table.'.id', '=', $user_role_department.'.user_id')
            ->join(
                $department_role_menu_table,
                function ($join) use ($department_role_menu_table, $user_role_department) {
                    $join->on($user_role_department.'.department_id', '=', $department_role_menu_table . '.department_id')
                        ->on($user_role_department.'.role_id', '=', $department_role_menu_table . '.role_id');
                }
            )->where($user_table . '.is_active', 1)
                    ->where($department_role_menu_table.'.menu_id', 6)
                    // ->where($user_role_department.'.role_id', 1)
                    ->select(
                        $user_table.'.id',
                        $user_table.'.firstname',
                        $user_table.'.lastname',
                        $user_role_department.'.role_id',
                        $user_role_department.'.department_id'
                    )->get();
    }

    /**
     * Get all user.
     *
     * @param Request $request [Request for get users]
     *
     * @return Object
     */
    public function getAllUsers($request)
    {
        $user = auth()->user();
        if ($user->hasRole('admin') || $user->can_view_all_users) {
            $users = User::with(['roles.userDepartments', 'roles'])->where('is_client', false);
        }else{
            $userIds = DepartmentRoleUser::whereIn('department_id', $user->departments->pluck('id'))->pluck('user_id');
            $users = User::with(['roles.userDepartments', 'roles'])->whereIn('id', $userIds)->where('is_client', false);
        }
        
        $userLists = $users;
        $totalData = $users->count();

        $columns = array(
            0 => 'id',
            1 => 'firstname',
            2 => 'lastname',
            3 => 'username',
            4 => 'is_active'
        );
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $userLists = $userLists->where(
                function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('user_generated_id', 'LIKE', "%{$search}%")
                        ->orWhere('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $userLists->count();
        }

        $userLists = $userLists->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $userLists
        );

        return $json_data;
    }

    /**
     * Set active deactive user.
     *
     * @param Request $request [Request for update user]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function setActiveDeactiveUser($request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = $request->get('is_active');

        if ($user->save()) {
            // --
            // Add activities
            createUserActivity(
                User::MODULE_NAME,
                $user->id,
                $request->method(),
                $user->firstname.' '.$user->lastname,
                $request->ip()
            );
            return $user;
        } else {
            return false;
        }
    }

    /**
     * Change user password.
     *
     * @param Request $user    [User object]
     * @param Request $input   [Input parameters]
     * @param Request $request [Request for change password]
     *
     * @return Response
     */
    public function changePassword($user, $input, $request)
    {
        $user->password = $input['password'];
        if ($user->save()) {
            $this->emailRepo->sendChangePasswordEmail($user, $input['password']);
            // --
            // Add activities
            createUserActivity(
                User::MODULE_NAME,
                $user->id,
                $request->method(),
                $user->firstname.' '.$user->lastname,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Change user email.
     *
     * @param Object  $user    [User object]
     * @param Request $input   [Input parameters]
     * @param Request $request [Request for change email]
     *
     * @return Response
     */
    public function changeEmail($user, $input, $request)
    {
        $user->email_verification_code = str_random(40);
        if ($user->save()) {
            $this->emailRepo->sendChangeEmailVerifyEmail($user, $input);
            // --
            // Add activities
            createUserActivity(
                User::MODULE_NAME,
                $user->id,
                $request->method(),
                $user->firstname.' '.$user->lastname,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int     $id      [Row id]
     * @param Request $request [Request for delete user]
     *
     * @return Boolean
     */
    public function delete($id, $request)
    {
        $user = User::find($id);
        if ($user) {
            $input = $request->all();
            // --
            // Delete avatar.
            if (!empty($user->avatar) && !in_array($user->avatar, $input['UserAvatars'])) {
                $this->fileHelper->deleteFile('profile', $user->avatar);
            }

            $this->_flushRoles(array(), $user);
            
            $user->delete();
            // --
            // Add activities
            createUserActivity(
                User::MODULE_NAME,
                $user->id,
                $request->method(),
                $user->firstname.' '.$user->lastname,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get client for dashboard.
     *
     * @param Request $request [Params for list client]
     *
     * @return object
     */
    public function getClientForDashboard($request)
    {
        $client = User::where('is_client', true)->orderBy('id', 'desc');

        if ($request->has('length')) {
            $client = $client->take($request->get('length'));
        }
        return $client->get();
    }

    /**
     * Store a newly imported resource in storage.
     *
     * @param Request $request [Request for import user]
     * @param Array   $csvData [User Data]
     *
     * @return Boolean
     */
    public function import($request, $csvData)
    {
        $importUserReqObj = new ImportUserRequest();
        $rules = $importUserReqObj->rules();
        $column = [
            "username",
            "firstname",
            "lastname",
            "email",
            "department",
            "role",
            "emp_id",
        ];

        $success = 0;
        $unsuccess = 0;
        $result = [];
        unset($csvData[0]); //Unset header.
        foreach ($csvData as $key => $value) {
            $post = [];
            foreach ($value as $key1 => $value1) {
                $post[$column[$key1]] = trim($value1);
            }
            $error[$key] = $this->_validateUser($post, $rules);

            if (is_array($error[$key])) {
                $error[$key] = json_encode($error[$key]);
                ++$unsuccess;
            } else {
                $this->createUserDepartment($post);
                ++$success;
            }
        }
        return compact('success', 'unsuccess', 'error');
    }

    /**
     * Validate user row data.
     *
     * @param Array $post [User row data]
     * @param Array $rules     [User validation rules]
     *
     * @return Boolean/Array
     */
    public function _validateUser($post, $rules)
    {
        $validator = Validator::make(
            $post,
            $rules
        );

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }

    /**
     * Import users with department/roles.
     *
     * @param array $post
     *
     * @return void
     */
    public function createUserDepartment($post)
    {
        $role = Role::whereName($post["role"])->first();
        $department = Department::whereName($post["department"])->first();

        if ($role && $department) {
            $roles = $department->roles->pluck('id')->toArray();
            
            if (!in_array($role->id, $roles)) {
                // --
                // Attach department roles
                array_push($roles, $role->id);
                $department->roles()->sync(array_unique($roles));
                // --
                // Assign menu permissions
                $menus = Menu::get();
                if (!empty($menus)) {
                    foreach ($menus as $key => $value) {
                        $departmentRoleMenu = new DepartmentRoleMenu();
                        $departmentRoleMenu->department_id = $department->id;
                        $departmentRoleMenu->role_id = $role->id;
                        $departmentRoleMenu->menu_id = $value->id;
                        $departmentRoleMenu->view = $value->id;
                        $departmentRoleMenu->created = 0;
                        $departmentRoleMenu->edited = 0;
                        $departmentRoleMenu->deleted = 0;
                        $departmentRoleMenu->save();
                    }
                }
            }
            // --
            // Create users
            $user = new User;
            $random_password = str_random(8);
            $user->user_generated_id = $this->getUserGeneratedId();
            $user->emp_id = strtoupper($post['emp_id']);
            $user->firstname = ucwords($post['firstname']);
            $user->lastname = ucwords($post['lastname']);
            $user->username = $post['username'];
            $user->email = $post['email'];
            $user->password = $random_password;
            $user->email_verification_code = str_random(40);
            $user->permission = 'all';

            if ($user->save()) {
                // --
                // Save user department/roles
                $departmentRoleUsers = new DepartmentRoleUser();
                $departmentRoleUsers->department_id = $department->id;
                $departmentRoleUsers->role_id = $role->id;
                $departmentRoleUsers->user_id = $user->id;
                $departmentRoleUsers->save();

                // --
                // Send email verification link
                try {
                    $this->emailRepo->sendActivateEmail(
                        $user,
                        $random_password
                    );
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }
        }
    }

    /**
     * Get all system admin users.
     *
     * @return Object
     */
    public static function getAdminUsers(){
        $adminUsers = Role::with([
            'users' => function ($query) {
                $query->select(config('core.acl.users_table').'.id', 'firstname', 'lastname', 'email')->where('is_active', 1);
            }]
        )->whereSlug('admin')->first();
        return $adminUsers->users;
    }

    /**
     * Change locale.
     *
     * @param String     $locale      [Locale]
     *
     * @return Object
     */
    public function changeLocale($locale)
    {
        $user = Auth::user();
        $user->language = $locale;

        if ($user->save()) {
            return $user;
        } else {
            return false;
        }
    }
}
