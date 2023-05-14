<?php
namespace Modules\ToDo\Repositories;

use Modules\User\Models\User\User;
use Modules\ToDo\Models\ToDo;

use Auth;

/**
 * Class ToDoRepository
 *
 * ToDo create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\ToDo
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ToDoRepository
{

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        $user = Auth::user();
        return $user->toDo()->get();
    }

    /**
     * Get the specified resource from storage
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return ToDo::findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create todo]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $user = Auth::user();
        $todo = new ToDo;
        $input['order'] = ToDo::count();
        $todo->fill($input);
        if ($user->toDo()->save($todo)) {
            // --
            // Add activities
            createUserActivity(
                Todo::MODULE_NAME,
                $todo->id,
                $request->method(),
                $todo->description,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update todo]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $todo = ToDo::findOrFail($id);
        $todo->fill($input);
        if ($todo->save()) {
            // --
            // Add activities
            createUserActivity(
                Todo::MODULE_NAME,
                $todo->id,
                $request->method(),
                $todo->description,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete todo]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $todo = ToDo::findOrFail($id);
        if ($todo->delete()) {
            // --
            // Add activities
            createUserActivity(
                Todo::MODULE_NAME,
                $todo->id,
                $request->method(),
                $todo->description,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Display a listing of the resource for list.
     *
     * @param Request $request [Params for list todos]
     *
     * @return Object
     */
    public function getTodos($request)
    {
        $todo_table = config('core.acl.todos_table');
        $modules_table = config('core.acl.modules_table');
        $projects_table = config('core.acl.projects_table');
        $task_table = config('core.acl.task_table');
        $defects_table = config('core.acl.defects_table');
        $incidents_table = config('core.acl.incidents_table');

        $user = Auth::user();
        $open_todos = $user->toDo()
            ->select(
                $todo_table.'.id',
                $todo_table.'.module_id',
                $todo_table.'.module_related_id',
                $todo_table.'.description',
                $todo_table.'.due_date',
                $todo_table.'.status',
                $modules_table.'.module_name',
                $projects_table.'.generated_id as project_generated_id',
                $task_table.'.generated_id as task_generated_id',
                $defects_table.'.generated_id as defect_generated_id',
                $incidents_table.'.generated_id as incident_generated_id'
            )
            ->join($modules_table, $modules_table.'.module_id', '=', $todo_table.'.module_id')
            ->leftjoin($projects_table, $projects_table.'.id', '=', $todo_table.'.module_related_id')
            ->leftjoin($task_table, $task_table.'.id', '=', $todo_table.'.module_related_id')
            ->leftjoin($defects_table, $defects_table.'.id', '=', $todo_table.'.module_related_id')
            ->leftjoin($incidents_table, $incidents_table.'.id', '=', $todo_table.'.module_related_id')
            ->where($todo_table.'.status', 1);

        $completed_todos = $user->toDo()
            ->select(
                $todo_table.'.id',
                $todo_table.'.module_id',
                $todo_table.'.module_related_id',
                $todo_table.'.description',
                $todo_table.'.due_date',
                $todo_table.'.status',
                $modules_table.'.module_name',
                $projects_table.'.generated_id as project_generated_id',
                $task_table.'.generated_id as task_generated_id',
                $defects_table.'.generated_id as defect_generated_id',
                $incidents_table.'.generated_id as incident_generated_id'
            )
            ->join($modules_table, $modules_table.'.module_id', '=', $todo_table.'.module_id')
            ->leftjoin($projects_table, $projects_table.'.id', '=', $todo_table.'.module_related_id')
            ->leftjoin($task_table, $task_table.'.id', '=', $todo_table.'.module_related_id')
            ->leftjoin($defects_table, $defects_table.'.id', '=', $todo_table.'.module_related_id')
            ->leftjoin($incidents_table, $incidents_table.'.id', '=', $todo_table.'.module_related_id')
            ->where($todo_table.'.status', 2);

        if ($request->has('module_id') && $request->has('module_related_id')) {
            $module_id = $request->get('module_id');
            $module_related_id = $request->get('module_related_id');

            $open_todos->where($todo_table.'.module_id', $module_id)
                ->where($todo_table.'.module_related_id', $module_related_id);

            $completed_todos->where($todo_table.'.module_id', $module_id)
                ->where($todo_table.'.module_related_id', $module_related_id);
        }

        if ($request->has('length')) {
            $completed_todos = $completed_todos->take($request->get('length'));
        }

        return collect([
            'open' => $open_todos->orderBy($todo_table.'.order', 'asc')->get(),
            'completed' => $completed_todos->orderBy($todo_table.'.order', 'asc')->get()
        ]);
    }

    /**
     * Update the specified resource.
     *
     * @param Request $request [Request for update todo list]
     *
     * @return Boolean
     */
    public function updateList($request)
    {
        $input = $request->all();
        foreach ($input['open'] as $key => $value) {
            $todo = ToDo::findOrFail($value['id']);
            $todo->status = 1;
            $todo->order = $key;
            $todo->save();
        }
        foreach ($input['completed'] as $key => $value) {
            $todo = ToDo::findOrFail($value['id']);
            $todo->status = 2;
            $todo->order = $key;
            $todo->save();
        }
        return true;
    }
}
