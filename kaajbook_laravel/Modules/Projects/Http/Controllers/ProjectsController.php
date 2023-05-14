<?php

namespace Modules\Projects\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Helper\Http\Controllers\PmHelperController;
use Modules\Projects\Http\Requests\CreateProjectRequest;
use Modules\Projects\Http\Requests\UpdateProjectRequest;
use Modules\Projects\Repositories\ProjectRepository;

/**
 * Class ProjectsController
 *
 * Project create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Projects
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectsController extends Controller
{
	protected $projectRepo;

	/**
	 * Instantiate a new repository instance.
	 *
	 * @param ProjectRepository $projectRepo [Object]
	 *
	 * @return void
	 */
	public function __construct(ProjectRepository $projectRepo)
	{
		$this->projectRepo = $projectRepo;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return $this->projectRepo->findAll();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getMyProjects()
	{
		return $this->projectRepo->getMyProjects();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request [Request for get projects]
	 *
	 * @return Response
	 */
	public function getAllProjects(Request $request)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(43, 'view')) {
			return response()->json("Access denied", 403);
		}

		return $this->projectRepo->getAllProjects($request);
	}

	/**
	 * Get project members and version.
	 *
	 * @return Response
	 */
	public function getProjectMembers()
	{
		return $this->projectRepo->getProjectMembers();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request [Request for create project]
	 *
	 * @return Response
	 */
	public function store(CreateProjectRequest $request)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(43, 'created')) {
			return response()->json("Access denied", 403);
		}

		$project = $this->projectRepo->create($request);

		if ($project) {
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
		if (!AdminHelper::can_action(43, 'view')) {
			return response()->json("Access denied", 403);
		}

		$project = $this->projectRepo->findById($id);
		if ($project) {
			return response()->json($project);
		}
		return response()->json('error', 401);
	}

	/**
	 * Show the specified resource.
	 *
	 * @param Int $id [Row id]
	 *
	 * @return Response
	 */
	public function getProjectById($id)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(43, 'view')) {
			return response()->json("Access denied", 403);
		}

		$project = $this->projectRepo->getProjectById($id);
		if ($project) {
			return response()->json($project);
		}
		return response()->json('error', 401);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request [Request for update project]
	 * @param Int     $id      [Row id]
	 *
	 * @return Response
	 */
	public function update(UpdateProjectRequest $request, $id)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(43, 'edited')) {
			return response()->json("Access denied", 403);
		}

		// --
		// Check project user permission
		if (!$this->projectRepo->checkPermission($id, 'edit')) {
			return response()->json("Access denied", 403);
		}

		$project = $this->projectRepo->update($request, $id);

		if ($project) {
			return response()->json('success');
		} else {
			return response()->json('error', 401);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request [Request for update project notes]
	 * @param Int     $id      [Row id]
	 *
	 * @return Response
	 */
	public function projectNotesUpdate(Request $request, $id)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(43, 'edited')) {
			return response()->json("Access denied", 403);
		}

		// --
		// Check project user permission
		if (!$this->projectRepo->checkPermission($id, 'edit')) {
			return response()->json("Access denied", 403);
		}

		$project = $this->projectRepo->projectNotesUpdate($request, $id);

		if ($project) {
			return response()->json('success');
		}
		return response()->json('error');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Request $request [Request for destroy project]
	 * @param Int     $id      [Row id]
	 *
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(43, 'deleted')) {
			return response()->json("Access denied", 403);
		}

		// --
		// Check role/permission
		if (!$this->projectRepo->checkPermission($id, 'delete')) {
			return response()->json("Access denied", 403);
		}

		if ($this->projectRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
	}

	/**
	 * Change project status.
	 *
	 * @param Request $request [Request for change project status]
	 * @param Int     $id      [Row id]
	 *
	 * @return Response
	 */
	public function changeProjectStatus(Request $request, $id)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(43, 'edited')) {
			return response()->json("Access denied", 403);
		}

		// --
		// Check project user permission
		if (!$this->projectRepo->checkPermission($id, 'edit')) {
			return response()->json("Access denied", 403);
		}
		
		if ($this->projectRepo->changeProjectStatus($request, $id)) {
			return response()->json('success');
		} else {
			return response()->json('error', 401);
		}
	}

	/**
	 * Store a newly imported resource in storage.
	 *
	 * @param Request $request [Request for import project]
	 *
	 * @return Response
	 */
	public function importProject(Request $request)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(43, 'created')) {
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
                0 => 'project name',
                1 => 'project version',
                2 => 'start date',
                3 => 'end date',
                4 => 'progress',
                5 => 'demo url',
                6 => 'assign group',
                7 => 'assign members',
                8 => 'status',
                9 => 'billing type',
                10 => 'fixed price',
                11 => 'hourly rate',
                12 => 'estimated hours',
                13 => 'description',
                14 => 'client',
                15 => 'created by',
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

			$res = $this->projectRepo->createImportProject($request, $csvData);
			return response()->json(['success' => $res]);
		} else {
			return response()->json(
				['error' => 'There are no any record in CSV.'],
				422
			);
		}
	}

	/**
	 * Get project planner project, sprint, tasks.
	 *
	 * @param Request $request [Request for import project]
	 *
	 * @return Response
	 */
	public function getProjectSprintTasks(Request $request)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(42, 'view')) {
			return response()->json("Access denied", 403);
		}

		return $this->projectRepo->getProjectSprintTasks($request);
	}

	/**
	 *  Display project all data.
	 *
	 * @param Request $request [Request for get project]
	 *
	 * @return Response
	 */
	public function getProjectForReport(Request $request)
	{
		// --
		// Check role/permission
		if (!AdminHelper::can_action(51, 'view')) {
			return response()->json("Access denied", 403);
		}

		if (!$request->has(['order','start','length'])) {
			return response()->json(
				["error" => "Something wrong with URL or parameters"],
				400
			);
		}

		return $this->projectRepo->getProjectForReport($request);
	}

	/**
	 * Get project permission.
	 *
	 * @param Request $request [Request for get permission]
	 *
	 * @return Response
	 */
	public function getProjectPermission(Request $request, $id)
	{
		$response = [
			'view' => false,
			'edit' => false,
			'delete' => false
		];
		
		if (AdminHelper::can_action(43, 'view')) {
			$response['view'] = $this->projectRepo->checkPermission($id, 'view');
		}
		if (AdminHelper::can_action(43, 'edited')) {
			$response['edit'] = $this->projectRepo->checkPermission($id, 'edit');
		}
		if (AdminHelper::can_action(43, 'deleted')) {
			$response['delete'] = $this->projectRepo->checkPermission($id, 'delete');
		}

		return $response;
	}
}
