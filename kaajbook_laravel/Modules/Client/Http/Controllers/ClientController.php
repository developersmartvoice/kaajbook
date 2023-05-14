<?php

namespace Modules\Client\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Client\Repositories\ClientRepository;
use Modules\Client\Http\Requests\CreateClientRequest;
use Modules\Client\Http\Requests\UpdateClientRequest;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class ClientController
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
class ClientController extends Controller
{
    protected $clientRepo;
    protected $helper;
    protected $adminHelper;

    /**
     * Instantiate a new repository instance.
     *
     * @param ClientRepository $client      [Object]
     * @param EmailsHelper     $helper      [Object]
     * @param AdminHelper      $adminHelper [Object]
     *
     * @return void
     */
    public function __construct(
        ClientRepository $client,
        EmailsHelper $helper,
        AdminHelper $adminHelper
    ) {
        $this->clientRepo = $client;
        $this->helper = $helper;
        $this->adminHelper = $adminHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->clientRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getClientsWithTrashed()
    {
        return $this->clientRepo->getClientsWithTrashed();
    }

    /**
     * Get all clients.
     *
     * @param Request $request [Request for get clients]
     *
     * @return Response
     */
    public function getAllClients(Request $request)
    {
        return $this->clientRepo->getAllClients($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateClientRequest $request [Request for create client]
     *
     * @return Response
     */
    public function store(CreateClientRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(11, 'created')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Save user
        $user = $this->clientRepo->create($request);

        // --
        // Send email verification link
        if (!empty($user)) {
            $this->helper->sendActivateEmail($user, $request->get('password'));
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Client has not been created.'],
                400
            );
        }
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
        return $this->clientRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientRequest $request [Request for update client]
     * @param Int                 $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateClientRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!$this->adminHelper->can_user_action(11, 'edit', $id)) {
            return response()->json("Access denied", 403);
        }

        $client = $this->clientRepo->update($request, $id);

        if ($client) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Client has not been found.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy client]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!$this->adminHelper->can_user_action(11, 'delete', $id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->clientRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Client has not been found.'], 400);
        }
    }

    /**
     * Active Deactive client.
     *
     * @param Request $request [Request for update user]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function setActiveDeactiveUser(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!$this->adminHelper->can_user_action(11, 'edit', $id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->clientRepo->setActiveDeactiveUser($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Client has not been found.'], 400);
        }
    }
}
