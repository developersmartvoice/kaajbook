<?php
/**
 * ToDo create, update, delete and view
 *
 * PHP version 7.1.3
 *
 * @category Administration
 * @package  Modules\ToDo
 * @author   Vipul Patel <vipul@chetsapp.com>
 * @license  Chetsapp Private Limited
 * @link     http://chetsapp.com
 */
namespace Modules\ToDo\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\ToDo\Repositories\ToDoRepository;
use Modules\ToDo\Http\Requests\CreateToDoRequest;
use Modules\ToDo\Http\Requests\UpdateToDoRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class ToDoController
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
class ToDoController extends Controller
{
    protected $toDoRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param ToDoRepository $toDo [Object]
     *
     * @return void
     */
    public function __construct(ToDoRepository $toDo)
    {
        $this->toDoRepo = $toDo;
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
        if (!AdminHelper::can_action(12, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->toDoRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateToDoRequest $request [Request for create todo]
     *
     * @return Response
     */
    public function store(CreateToDoRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(12, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->toDoRepo->create($request)) {
            return response()->json('success', 200);
        } else {
            return response()->json(['error' => 'Error while creating.'], 400);
        }
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
        if (!AdminHelper::can_action(12, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->toDoRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update todo]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateToDoRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(12, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->toDoRepo->update($request, $id)) {
            return response()->json('success', 200);
        } else {
            return response()->json(['error' => 'Error while updating.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy todo]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(12, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->toDoRepo->delete($request, $id)) {
            return response()->json('success', 200);
        } else {
            return response()->json(['error' => 'Error while deleting.'], 400);
        }
    }

    /**
     * Display a listing of the resource for list.
     *
     * @param Request $request [Params for list todos]
     *
     * @return Response
     */
    public function getTodos(Request $request)
    {
        return $this->toDoRepo->getTodos($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update todo list]
     *
     * @return Response
     */
    public function updateList(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(12, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $todo = $this->toDoRepo->updateList($request);

        if ($todo) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
}
