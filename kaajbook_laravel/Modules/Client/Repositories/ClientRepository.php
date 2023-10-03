<?php

namespace Modules\Client\Repositories;

use Modules\Client\Models\Client;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Helpers\UploadFileHelper;
use Modules\User\Models\Department\Department;
use Modules\User\Models\Department\DepartmentRoleUser;
use Modules\User\Models\User\User;
use Modules\User\Repositories\UserRepository;

/**
 * Class ClientRepository
 *
 * Client create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Client
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ClientRepository
{
    protected $fileHelper;
    protected $userRepo;
    protected $emailRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param UploadFileHelper $fileHelper [Object]
     * @param UserRepository $userRepo [Object]
     * @param EmailsHelper     $emailRepo  [Object]
     *
     * @return void
     */
    public function __construct(
        UploadFileHelper $fileHelper,
        UserRepository $userRepo,
        EmailsHelper $emailRepo
    ) {
        $this->fileHelper = $fileHelper;
        $this->userRepo = $userRepo;
        $this->emailRepo = $emailRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return User::with(['clients' , 'roles'])
            ->where('is_client', true)
            ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function getClientsWithTrashed()
    {
        return User::where('is_client', true)
            ->withTrashed()
            ->get();
    }

    /**
     * Get all user.
     *
     * @param Request $request [Request for get clients]
     *
     * @return Object
     */
    public function getAllClients($request)
    {
        $users_table = config('core.acl.users_table');
        $clients_table = config('core.acl.clients_table');

        $user = auth()->user();
        if ($user->hasRole('admin' || 'project_manager') || $user->can_view_all_users) {
            $clients = User::with(['roles.userdepartments', 'clients'])->where('is_client', true);
        }else{
            $clientIds = DepartmentRoleUser::whereIn('department_id', $user->departments->pluck('id'))->pluck('user_id');
            $clients = User::with(['roles.userdepartments', 'clients'])->whereIn('id', $clientIds)->where('is_client', true);
        }

        $totalData = $clients->count();
        $totalFiltered = $totalData;

        $columns = array(
            0 => 'id',
            1 => 'firstname',
            2 => 'lastname',
            3 => 'username',
            6 => 'is_active'
        );
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $clients = $clients->where(
                function ($query) use ($search) {
                    $query->where('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('username', 'LIKE', "%{$search}%")
                        ->orWhere('is_active', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $clients->count();
        }

        $clients = $clients->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $clients
        );

        return $json_data;
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
        $user = User::with(['clients', 'departments'])
            ->where('id', $id)
            ->first();

        if (!empty($user)) {
            foreach ($user->departments as $department) {
                $department->roles = Department::where('id', $department->id)->with(
                    ['userRoles' => function ($query) use ($user) {
                        $query->wherePivot('user_id', $user->id);
                    }]
                )->first()->userRoles;
            }
        }
        return $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create client]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $user = new User;
        $input = $request->all();
        $user = $this->_createUserStub($user, $input);

        // --
        // Save user and profile
        if ($user->save()) {
            // --
            // Create client
            $client = new Client;
            $client->fill($input['clients']);
            $user->clients()->save($client);

            // --
            // Attach/Detach roles
            if ($roles = $request->get('department_roles')) {
                $this->_flushRoles($roles, $user);
            }

            // Send client creation notification to admins.
            $adminUsers = $this->userRepo->getAdminUsers();
            $this->emailRepo->sendClientCreationEmailToAdmin($adminUsers, $user);

            // --
            // Add activities
            createUserActivity(
                Client::MODULE_NAME,
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
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update client]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $user = User::find($id);

        if( !empty($user->avatar) && ( $user->avatar != $input['avatar']) && !in_array($user->avatar , $input['UserAvatars']) ){
            $this->fileHelper->deleteFile('profile',$user->avatar);
        }

        $user = $this->_createUserStub($user, $input);

        // --
        // Save user
        if ($user->save()) {
            // --
            // Update client
            $client = Client::where('user_id', $user->id)->first();
            if (!empty($client)) {
                $client->fill($input['clients']);
                $user->clients()->save($client);
            }

            // --
            // Update roles
            if ($roles = $request->get('department_roles')) {
                $this->_flushRoles($roles, $user);
            }

            // --
            // Add activities
            createUserActivity(
                Client::MODULE_NAME,
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
     * @param Request $request [Request for delete client]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $user = User::find($id);
        
        if ($user) {
            $input = $request->all();
            // --
            // Delete avatar.
            if( !empty($user->avatar) && !in_array($user->avatar , $input['UserAvatars']) ){
                $this->fileHelper->deleteFile('profile',$user->avatar);
            }

            $this->_flushRoles(array(), $user);
            
            $client = Client::where('user_id', $user->id)->delete();

            // --
            // Add activities
            createUserActivity(
                Client::MODULE_NAME,
                $user->id,
                $request->method(),
                $user->firstname.' '.$user->lastname,
                $request->ip()
            );
            $user->delete();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Create user stub
     *
     * @param Object $user  [User object]
     * @param Array  $input [Input array]
     *
     * @return Object
     */
    private function _createUserStub($user, $input)
    {
        $user->firstname = ucwords($input['firstname']);
        $user->lastname = ucwords($input['lastname']);
        $user->username = $input['username'];
        $user->email = $input['email'];
        $user->country = isset($input['country']) ? $input['country'] : null;
        $user->language = isset($input['language']) ? $input['language'] : 'en';
        $user->phone = isset($input['phone']) ? $input['phone'] : null;
        $user->mobile = isset($input['mobile']) ? $input['mobile'] : null;
        $user->address = isset($input['address']) ? $input['address'] : null;
        $user->skype = isset($input['skype']) ? $input['skype'] : null;
        $user->permission = null;
        if (isset($input['avatar'])) {
            $user->avatar = $this->fileHelper->uploadImage(
                'user_avtar',
                $input['avatar']
            );
        }
        if (!$user->id) {
            $user->user_generated_id = $input['user_generated_id'];
            $user->is_client = true;
            $user->password = $input['password'];
            $user->email_verification_code = str_random(40);
        }
        return $user;
    }

    /**
     * Flush roles
     *
     * @param Array  $roles [Roles array]
     * @param Object $user  [User object]
     *
     * @return Boolean
     */
    private function _flushRoles($roles, $user)
    {
        $departmentRoleUsers = DepartmentRoleUser::where('user_id', $user->id)
            ->get();
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
     * Set active deactive client.
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
            return $user;
        } else {
            return false;
        }
    }
}
