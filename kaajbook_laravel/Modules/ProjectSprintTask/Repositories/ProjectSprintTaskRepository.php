<?php
namespace Modules\ProjectSprintTask\Repositories;

use Modules\Projects\Models\Project;
use Modules\ProjectSprintTask\Models\ProjectSprintTask;
use Modules\ProjectPlannerSprint\Models\ProjectPlannerSprint;

use Auth;

/**
 * Class ProjectSprintTaskRepository
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
class ProjectSprintTaskRepository
{

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return ProjectSprintTask::all();
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
        return ProjectSprintTask::with([
            'taskMembers' => function ($query) {
                $query->select(
                    config('core.acl.users_table').'.id',
                    'firstname',
                    'lastname'
                );
            }
        ])->find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create PS task]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $projectSprintTask = new ProjectSprintTask;
        $projectSprintTask->fill($input);

        if ($projectSprintTask->save()) {
            // --
            // Save members
            $projectSprintTask->taskMembers()->sync($input['assign_to']);
            // --
            // Add activities
            createUserActivity(
                ProjectSprintTask::MODULE_NAME,
                $projectSprintTask->id,
                $request->method(),
                $projectSprintTask->task_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update PS task]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $projectSprintTask = ProjectSprintTask::find($id);
        $projectSprintTask->fill($input);
        if ($projectSprintTask->save()) {
            if ($input['status'] == 2) {
                $data['status'] = 2;
                if (isset($input['project_sprint_id'])) {
                    $projectPlannerSprint = ProjectPlannerSprint::find($input['project_sprint_id']);
                    $projectPlannerSprint->fill($data)->save();
                    $projectPlanner = Project::find($projectPlannerSprint->project_id);
                    $projectPlanner->fill($data)->save();
                } else{
                    $projectPlanner = Project::find($projectSprintTask->project_id);
                    $projectPlanner->fill($data)->save();
                }
            }

            $projectSprintTask->taskMembers()->sync($input['assign_to']);
            // --
            // Add activities
            createUserActivity(
                ProjectSprintTask::MODULE_NAME,
                $projectSprintTask->id,
                $request->method(),
                $projectSprintTask->task_name,
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
     * @param Request $request [Request for delete PS task]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function delete($request, $id)
    {
        $projectSprintTask = ProjectSprintTask::findOrFail($id);
        $projectSprintTask->taskMembers()->detach();
        if ($projectSprintTask->delete()) {
            // --
            // Add activities
            createUserActivity(
                ProjectSprintTask::MODULE_NAME,
                $projectSprintTask->id,
                $request->method(),
                $projectSprintTask->task_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Move project sprint tasks.
     *
     * @param Request $request [Request for move sprint task]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function moveSprintTask($request, $id)
    {
        $sprint_id = $request->get('sprint_id');
        $projectSprintTask = ProjectSprintTask::find($id);
        $projectSprintTask->project_sprint_id = $sprint_id;

        if ($projectSprintTask->save()) {
            // --
            // Add activities
            createUserActivity(
                ProjectSprintTask::MODULE_NAME,
                $projectSprintTask->id,
                $request->method(),
                $projectSprintTask->task_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Change sprint task status.
     *
     * @param Request $request [Request for change sprint task status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeStatus($request, $id)
    {
        $status = $request->get('status');
        $projectSprintTask = ProjectSprintTask::findOrFail($id);
        if ($projectSprintTask) {
            $input['status'] = $status;
            if ($projectSprintTask->fill($input)->save()) {
                if ($status == 2) {
                    if ($projectSprintTask->project_sprint_id) {
                        $projectPlannerSprint = ProjectPlannerSprint::find($projectSprintTask->project_sprint_id);
                        $projectPlannerSprint->fill($input)->save();
                        $project = Project::find($projectPlannerSprint->project_id);
                        $project->fill($input)->save();
                    } else{
                        $projectPlanner = Project::find($projectSprintTask->project_id);
                        $projectPlanner->fill($input)->save();
                    }
                }
                // --
                // Add activities
                createUserActivity(
                    ProjectSprintTask::MODULE_NAME,
                    $projectSprintTask->id,
                    $request->method(),
                    $projectSprintTask->task_name,
                    $request->ip(),
                    null,
                    true
                );
            }
            
            return true;
        }
        return false;
    }
}
