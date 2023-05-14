<?php

namespace Modules\ProjectPlannerSprint\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\ProjectPlannerSprint\Repositories\ProjectPlannerSprintRepository;
use Modules\ProjectPlannerSprint\Http\Requests\ProjectPlannerSprintRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class ProjectPlannerSprintController
 *
 * Project Planner Sprint create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectPlannerSprint
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectPlannerSprintController extends Controller
{
    protected $projectPlannerSprintRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param ProjectPlannerSprintRepository $projectPlannerSprint [Object]
     *
     * @return void
     */
    public function __construct(ProjectPlannerSprintRepository $projectPlannerSprint)
    {
        $this->projectPlannerSprintRepo = $projectPlannerSprint;
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

        return $this->projectPlannerSprintRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectPlannerSprintRequest $request [Request for create PP sprint]
     *
     * @return Response
     */
    public function store(ProjectPlannerSprintRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'created')) {
            return response()->json("Access denied", 403);
        }

        $projectPlannerSprint = $this->projectPlannerSprintRepo->create($request);

        if ($projectPlannerSprint) {
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

        return $this->projectPlannerSprintRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectPlannerSprintRequest $request [Request for update PP sprint]
     * @param Int                         $id      [Row id]
     *
     * @return Response
     */
    public function update(ProjectPlannerSprintRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $projectPlannerSprint = $this->projectPlannerSprintRepo->update(
            $request,
            $id
        );

        if ($projectPlannerSprint) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy PP sprint]
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

        if ($this->projectPlannerSprintRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Change sprint status.
     *
     * @param Request $request [Request for change sprint status]
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
        
        if ($this->projectPlannerSprintRepo->changeStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Get sprint by project.
     *
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function getSprintByProject($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(42, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->projectPlannerSprintRepo->getSprintByProject($id);
    }
}
