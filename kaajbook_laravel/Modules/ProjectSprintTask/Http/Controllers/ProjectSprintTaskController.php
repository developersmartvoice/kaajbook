<?php

namespace Modules\ProjectSprintTask\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\ProjectSprintTask\Repositories\ProjectSprintTaskRepository;
use Modules\ProjectSprintTask\Http\Requests\ProjectSprintTaskRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class ProjectSprintTaskController
 *
 * Project Sprint Task create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectSprintTask
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectSprintTaskController extends Controller
{
    protected $projectSprintTaskRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param ProjectSprintTaskRepository $projectSprintTask [Object]
     *
     * @return void
     */
    public function __construct(ProjectSprintTaskRepository $projectSprintTask)
    {
        $this->projectSprintTaskRepo = $projectSprintTask;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->projectSprintTaskRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectSprintTaskRequest $request [Request for create PS task]
     *
     * @return Response
     */
    public function store(ProjectSprintTaskRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'created')) {
            return response()->json("Access denied", 403);
        }

        $projectSprintTask = $this->projectSprintTaskRepo->create($request);

        if ($projectSprintTask) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
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
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->projectSprintTaskRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update PS task]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function update(ProjectSprintTaskRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $projectSprintTask = $this->projectSprintTaskRepo->update($request, $id);

        if ($projectSprintTask) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy PS task]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->projectSprintTaskRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Move project sprint tasks.
     *
     * @param Request $request [Request for move sprint task]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function moveSprintTask(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $request->validate(['sprint_id' => 'required']);
        
        $projectSprintTask = $this->projectSprintTaskRepo->moveSprintTask(
            $request,
            $id
        );

        if ($projectSprintTask) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Change sprint task status.
     *
     * @param Request $request [Request for change sprint task status]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->projectSprintTaskRepo->changeStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
