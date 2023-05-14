<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\ChangeEmailRequest;
use Modules\User\Http\Requests\ChangePasswordRequest;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Models\User\User;
use Modules\User\Repositories\UserRepository;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class UserController
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
class UserController extends Controller
{
    protected $userRepo;
    protected $helper;
    protected $adminHelper;

    /**
     * Instantiate a new repository instance.
     *
     * @param UserRepository $userRepo    [Object]
     * @param EmailsHelper   $helper      [Object]
     * @param AdminHelper    $adminHelper [Object]
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepo,
        EmailsHelper $helper,
        AdminHelper $adminHelper
    ) {
        $this->userRepo = $userRepo;
        $this->helper = $helper;
        $this->adminHelper = $adminHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function index()
    {
        return $this->userRepo->getUsers();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function getUserIdName()
    {
        return $this->userRepo->getUserIdName();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function getUserkeyBy()
    {
        return $this->userRepo->getUserkeyBy();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function getMailUsers()
    {
        return $this->userRepo->getMailUsers();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create user]
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(6, 'created')) {
            return response()->json("Access denied", 403);
        }

        $user = $this->userRepo->create($request);
        // --
        // Send email verification link
        try {
            $this->helper->sendActivateEmail($user,$request->get('password'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json('success');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->userRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update user]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!$this->adminHelper->can_user_action(6, 'edit', $id)
        ) {
            return response()->json("Access denied", 403);
        }

        if ($this->userRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'User has not been found.'], 400);
        }
    }

    /**
     * Retrive last insert id
     *
     * @return id
     */
    public function getUserGeneratedId()
    {
        $generated_id = $this->userRepo->getUserGeneratedId();
        if ($generated_id) {
            return response()->json($generated_id);
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Get user permissions.
     *
     * @param Request $request [Request for get user permission]
     *
     * @return Object
     */
    public function getUserPermissions(Request $request)
    {
        return $this->userRepo->getUserPermissions();
    }

    /**
     * Get all users.
     *
     * @param Request $request [Request for get users]
     *
     * @return Object
     */
    public function getAllUsers(Request $request)
    {
        return $this->userRepo->getAllUsers($request);
    }

    /**
     * Active Deactive user.
     *
     * @param Request $request [Request for update user]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function setActiveDeactiveUser(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!$this->adminHelper->can_user_action(6, 'edit', $id)
        ) {
            return response()->json("Access denied", 403);
        }

        if ($this->userRepo->setActiveDeactiveUser($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'User has not been found.'], 400);
        }
    }

    /**
     * Change user password.
     *
     * @param Request $request [Request for change password]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changePassword(ChangePasswordRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!$this->adminHelper->can_user_action(6, 'edit', $id)
        ) {
            return response()->json("Access denied", 403);
        }

        $formData = $request->all();
        $user = User::find($id);

        if (!empty($user)) {
            if (!\Hash::check($formData['old_password'], $user->password)) {
                return response()->json(
                    ['error' => 'The specified password does not match the database password.'],
                    422
                );
            }

            if ($response = $this->userRepo->changePassword($user, $formData, $request)) {
                return response()->json('success');
            } else {
                return $response;
            }
        } else {
            return response()->json(['error' => 'User has not been found.'], 400);
        }
    }

    /**
     * Change user email.
     *
     * @param Request $request [Request for change email]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeEmail(ChangeEmailRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!$this->adminHelper->can_user_action(6, 'edit', $id)
        ) {
            return response()->json("Access denied", 403);
        }

        $formData = $request->all();
        $user = User::findOrFail($id);
        // --
        // Validations
        if (!\Hash::check($formData['password'], $user->password)) {
            return back()->with('error', 'The specified password does not match the database password');
        }

        if ($response = $this->userRepo->changeEmail($user, $formData, $request)) {
            return response()->json('success');
        } else {
            return $response;
        }
    }

    /**
     * Get invite user by email.
     *
     * @param \Illuminate\Http\Request $request [Request]
     * @param int                      $id      [Id]
     *
     * @return \Illuminate\Http\Response
     */
    public function sendInviteUserMail(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $res = $this->helper->sendInviteUserMail($user);
        if($res['status']){
            return response()->json('success');
        }else{
            return response()->json([ 'error' => $res['msg'] ], 400);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Int     $id      [Row id]
     * @param Request $request [Request for destroy user]
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        // --
        // Check role/permission
        if (!$this->adminHelper->can_user_action(6, 'delete', $id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->userRepo->delete($id, $request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'User has not been found.'], 400);
        }
    }

    /**
     * Get client for dashboard.
     *
     * @param Request $request [Params for list client]
     *
     * @return Response
     */
    public function getClientForDashboard(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(6, 'view')) {
            return response()->json("Access denied", 403);
        }
        
        return $this->userRepo->getClientForDashboard($request);
    }

    /**
     * Store a newly imported resource in storage.
     *
     * @param Request $request [Request for import user]
     *
     * @return Response
     */
    public function import(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(6, 'created')) {
            return response()->json("Access denied", 403);
        }

        $request->validate(['csv_file' => 'required']);
        $error_file = [];
        $input = $request->all();
        $csvData  = explode("\n", $input['csv_file']);
        foreach ($csvData as $key => $value) {
            $csvData[$key] = explode(',', $value);
        }

        if(count($csvData) >= 2){
            $header =  array(
                0 => 'username',
                1 => 'firstname',
                2 => 'lastname',
                3 => 'email',
                4 => 'department name',
                5 => 'role name',
                6 => 'employment id'
            );
            $data = $csvData[0];
            foreach ($header as $key => $value) {
                if(isset($data[$key])){
                    if (strtolower(trim($data[$key])) !== $value) {
                        $error_file[] = $value.' field is required';
                    }
                }else{
                    $error_file[] = $value.' field is required';
                }
            }
            if (is_array($error_file) && count($error_file) > 0) {
                return response()->json(['error' => $error_file ], 422);
            }

            $res = $this->userRepo->import($request, $csvData);
            return response()->json(['success' => $res]);

        }else{
            return response()->json(['error' => 'There are no any record in CSV.'],422);
        }
    }

    /**
     * Change locale.
     *
     * @param String     $locale      [Locale]
     *
     * @return Response
     */
    public function changeLocale($locale)
    {
        if ($this->userRepo->changeLocale($locale)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'User has not been found.'], 400);
        }
    }
}
