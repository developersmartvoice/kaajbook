<?php

namespace Modules\Team\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Team\Repositories\TeamRepository;
use Modules\Team\Http\Requests\CreateTeamRequest;
use Modules\Team\Http\Requests\UpdateTeamRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class TeamController
 *
 * Team create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Team
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class TeamController extends Controller
{
    protected $teamRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param TeamRepository $team [Object]
     *
     * @return void
     */
    public function __construct(TeamRepository $team)
    {
        $this->teamRepo = $team;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->teamRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get team]
     *
     * @return Response
     */
    public function getAllTeams(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(7, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->teamRepo->getAllTeams($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTeamRequest $request [Request for create team]
     *
     * @return Response
     */
    public function store(CreateTeamRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(7, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->teamRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Team has not been created.'], 400);
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
        if (!AdminHelper::can_action(7, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->teamRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTeamRequest $request [Request for update team]
     * @param int               $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateTeamRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(7, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->teamRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Team has not been found.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy team]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(7, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->teamRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Team has not been found.'], 400);
        }
    }

    /**
     * Get teams for team board.
     *
     * @return Response
     */
    public function getTeamboard()
    {
        return $this->teamRepo->getTeamboard();
    }

    /**
     * Store a newly imported resource in storage.
     *
     * @param Request $request [Request for import team]
     *
     * @return Response
     */
    public function import(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(7, 'created')) {
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
                0 => 'team name',
                1 => 'team members',
                2 => 'team leader',
                3 => 'description'
            );
            $data = $csvData[0];
            foreach ($header as $key => $value) {
                if (isset($data[$key]) && strtolower(trim($data[$key])) !== $value) {
                    $error_file[] = $value.' field is required';
                }
            }

            if (is_array($error_file) && count($error_file) > 0) {
                return response()->json(['error' => $error_file ], 422);
            }

            $res = $this->teamRepo->import($request, $csvData);
            return response()->json(['success' => $res]);

        }else{
            return response()->json(['error' => 'There are no any record in CSV.'],422);
        }
    }
}
