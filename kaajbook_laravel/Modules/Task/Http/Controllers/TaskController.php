<?php

namespace Modules\Task\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Task\Http\Requests\ConvertTaskRequest;
use Modules\Task\Http\Requests\CreateTaskRequest;
use Modules\Task\Http\Requests\UpdateTaskRequest;
use Modules\Task\Repositories\TaskRepository;

/**
 * Class TaskController
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
class TaskController extends Controller
{
    protected $taskRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param TaskRepository $task [Object]
     *
     * @return void
     */
    public function __construct(TaskRepository $task)
    {
        $this->taskRepo = $task;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get task]
     *
     * @return Response
     */
    public function getAllTask(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->taskRepo->getAllTask($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create task]
     *
     * @return Response
     */
    public function store(CreateTaskRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'created')) {
            return response()->json("Access denied", 403);
        }

        $task = $this->taskRepo->create($request);

        if ($task) {
            return response()->json("success");
        }
        return response()->json('Task is not created successfully', 401);
    }

    /**
     * Show the specified resource.
     *
     * @param int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->taskRepo->getById($id);
    }

    /**
     * Retrieve parent task of particular subtask.
     *
     * @param int $parent_task_id [Parent task row id]
     *
     * @return Response
     */
    public function getParentTask($parent_task_id)
    {
        return $this->taskRepo->getParentTask($parent_task_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskRequest $request [Request for update task]
     * @param int               $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->taskRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->taskRepo->update($request, $id)) {
            return response()->json("success");
        } else {
            return response()->json("error", 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update task notes]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function taskNotesUpdate(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->taskRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        $task = $this->taskRepo->taskNotesUpdate($request, $id);

        if ($task) {
            return response()->json('success');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy task]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->taskRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->taskRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Change staus of particular record.
     *
     * @param Request $request [Request for change task status]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function changeStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'edited')) {
            return response()->json("Access denied", 403);
        }

        // --
        // Check task user permission
        if (!$this->taskRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->taskRepo->changeStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Change task priority.
     *
     * @param Request $request [Request for change task priority]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeTaskPriority(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        // --
        // Check task user permission
        if (!$this->taskRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }

        if ($this->taskRepo->changeTaskPriority($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get task by project]
     *
     * @return Response
     */
    public function getTaskForTaskBoard(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'view')) {
            return response()->json("Access denied", 403);
        }
        
        return $this->taskRepo->getTaskForTaskBoard($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update task status]
     *
     * @return Response
     */
    public function updateKanban(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $task = $this->taskRepo->updateKanban($request);

        if ($task) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * Retrive last insert id.
     *
     * @return id
     */
    public function getGeneratedId()
    {
        $generated_id = $this->taskRepo->getGeneratedId();
        if ($generated_id) {
            return response()->json($generated_id);
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Store a newly imported resource in storage.
     *
     * @param Request $request [Request for import task]
     *
     * @return Response
     */
    public function importTask(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'created')) {
            return response()->json("Access denied", 403);
        }

        $request->validate(['csv_file' => 'required']);
        $error_file = [];
        $input = $request->all();
        
        $csvData  = explode("\n", $input['csv_file']);
        foreach ($csvData as $key => $value) {
            $csvData[$key] = explode(',', $value);
        }

        if (count($csvData) >= 2) {
            $header =  array(
                0 => 'task name',
                1 => 'project name',
                2 => 'project version',
                3 => 'planned start date',
                4 => 'planned end date',
                5 => 'task start date',
                6 => 'task end date',
                7 => 'estimate hours',
                8 => 'status',
                9 => 'assigned to',
                10 => 'created by',
                11 => 'priority',
                12 => 'progress',
                13 => 'description'
            );
            $data = $csvData[0];
            foreach ($header as $key => $value) {
                if (isset($data[$key])) {
                    if (strtolower(trim($data[$key])) !== $value) {
                        $error_file[] = $value.' field is required';
                    }
                } else {
                    $error_file[] = $value.' field is required';
                }
            }
            if (is_array($error_file) && count($error_file) > 0) {
                return response()->json(['error' => $error_file ], 422);
            }
            
            $res = $this->taskRepo->createImportTask($request, $csvData);
            return response()->json(['success' => $res]);
        } else {
            return response()->json(
                ['error' => 'There are no any record in CSV.'],
                422
            );
        }
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
        $generated_id = $this->taskRepo->getSubTaskCountByParent($parent_task_id);
        if ($generated_id) {
            return response()->json($generated_id);
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Get tasks for report.
     *
     * @param Request $request [Request for get task]
     *
     * @return Response
     */
    public function getTaskForReport(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(51, 'view')) {
            return response()->json("Access denied", 403);
        }
        
        return $this->taskRepo->getTaskForReport($request);
    }

    /**
     * Get task permission.
     *
     * @param Request $request [Request for get permission]
     *
     * @return Response
     */
    public function getTaskPermission(Request $request, $id)
    {
        return $this->taskRepo->checkPermission($id, $request->get('type'));
    }

    /**
     * Display a listing of the resource for calendar.
     *
     * @return Array
     */
    public function getTasksForCalendar()
    {
        return $this->taskRepo->getTasksForCalendar();
    }

    /**
     * Convert project planner task to task.
     *
     * @param Request $request [Request for create task]
     *
     * @return Response
     */
    public function convertSprintTaskToTask(ConvertTaskRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(44, 'created')) {
            return response()->json("Access denied", 403);
        }

        $task = $this->taskRepo->convertSprintTaskToTask($request);

        if ($task) {
            return response()->json("success");
        }
        return response()->json('Task is not created successfully', 401);
    }
}
