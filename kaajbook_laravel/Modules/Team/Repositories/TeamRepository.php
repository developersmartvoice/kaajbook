<?php
namespace Modules\Team\Repositories;

use Modules\User\Models\User\User;
use Modules\Team\Models\Team;
use Modules\Team\Http\Requests\ImportTeamRequest;
use DB;
use Validator;

/**
 * Class TeamRepository
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
class TeamRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Team::with(
            ['members' => function ($query) {
                $query->select(
                    config('core.acl.users_table').'.id',
                    config('core.acl.users_table').'.firstname',
                    config('core.acl.users_table').'.lastname',
                    config('core.acl.users_table').'.avatar'
                );
            }]
        )->orderBy('created_at', 'desc')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get team]
     *
     * @return Array
     */
    public function getAllTeams($request)
    {
        $users_table = config('core.acl.users_table');
        $teams_table = config('core.acl.teams');

        $totalData = Team::with(['members'])->count();

        $columns = array(
            0 =>'id',
            1 =>'team_name',
            3 =>'firstname'
        );

        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $team = Team::with(
            ['members' => function ($query) {
                $query->select(
                    config('core.acl.users_table').'.id',
                    config('core.acl.users_table').'.firstname',
                    config('core.acl.users_table').'.lastname',
                    config('core.acl.users_table').'.avatar'
                );
            }]
        )
        ->join($users_table.' as user', 'user.id', '=', $teams_table.'.team_leader')
        ->select(
            config('core.acl.teams').'.*',
            'user.firstname',
            'user.lastname',
            'user.avatar'
        );

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $team = $team->where($teams_table.'.id', 'LIKE', "%{$search}%")
                ->orWhere('team_name', 'LIKE', "%{$search}%")
                ->orWhere('firstname', 'LIKE', "%{$search}%")
                ->orWhere('lastname', 'LIKE', "%{$search}%");
                
            $totalFiltered = $team->count();
        }

        $team = $team->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $team
        );

        return $json_data;
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
        return Team::with(
            ['members' => function ($query) {
                $query->select(
                    config('core.acl.users_table').'.id',
                    config('core.acl.users_table').'.firstname',
                    config('core.acl.users_table').'.lastname',
                    config('core.acl.users_table').'.avatar'
                );
            }]
        )->where('id', $id)->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create team]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $team = new Team;
        $input = $request->all();
        $team->fill($input);
        if ($team->save()) {
            // --
            // Save team members
            $team->members()->sync($input['members']);

            // --
            // Add activities
            createUserActivity(
                Team::MODULE_NAME,
                $team->id,
                $request->method(),
                $team->team_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update team]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $team = Team::findOrFail($id);
        $input = $request->all();
        if ($team->fill($input)->save()) {
            $team->members()->sync($input['members']);
            // --
            // Add activities
            createUserActivity(
                Team::MODULE_NAME,
                $team->id,
                $request->method(),
                $team->team_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete team]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $team = Team::findOrFail($id);

        if (!empty($team)) {
            $team->members()->sync(array());

            // --
            // Add activities
            createUserActivity(
                Team::MODULE_NAME,
                $team->id,
                $request->method(),
                $team->team_name,
                $request->ip()
            );

            $team->delete();
            return true;
        }
        return false;
    }

    /**
     * Get teams for team board.
     *
     * @return object
     */
    public function getTeamboard()
    {
        return Team::with(
            [
                'members' => function ($query) {
                    $query->select(
                        config('core.acl.users_table').'.id',
                        config('core.acl.users_table').'.firstname',
                        config('core.acl.users_table').'.lastname',
                        config('core.acl.users_table').'.avatar'
                    );
                },
                'project'
            ]
        )
        ->orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly imported resource in storage.
     *
     * @param Request $request [Request for import team]
     * @param Array   $csvData [Team Data]
     *
     * @return Boolean
     */
    public function import($request, $csvData)
    {
        $importTeamReqObj = new ImportTeamRequest();
        $rules = $importTeamReqObj->rules();
        $column = [
            "team_name",
            "members",
            "team_leader",
            "description"
        ];
        $success = 0;
        $unsuccess = 0;
        $result = [];
        unset($csvData[0]); //Unset header.
        foreach ($csvData as $key => $value) {
            $post = [];
            foreach ($value as $key1 => $value1) {
                $post[$column[$key1]] = trim($value1);
            }
            $error[$key] = $this->_validateUser($post,$rules);

            if (is_array($error[$key])) {
                $error[$key] = json_encode($error[$key]);
                ++$unsuccess;
            } else {
                $team = $this->createTeam($request, $post);
                ++$success;
            }
        }
        return compact('success', 'unsuccess', 'error');
    }

    /**
     * Validate team row data.
     *
     * @param Array $post [Team row data]
     * @param Array $rules     [Team validation rules]
     *
     * @return Boolean/Array
     */
    public function _validateUser($post, $rules)
    {
        $validator = Validator::make(
            $post,
            $rules
        );

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }

    /**
     * Import team.
     *
     * @param array $post
     *
     * @return void
     */
    public function createTeam($request, $post)
    {        
        // --
        // Team leader
        $user = User::whereEmail(trim($post['team_leader']))->first();
        $team = new Team();
        $team->fill([
            'team_name' => $post['team_name'],
            'team_leader' => $user->id,
            'description' => $post['description']
        ]);
        if ($team->save()) {
            // --
            // Sync team members.
            $users = explode("-", $post['members']);
            $users = User::whereIn('email', $users)->pluck('id');
            $team->members()->sync($users);

            // --
            // Add activities
            createUserActivity(
                Team::MODULE_NAME,
                $team->id,
                $request->method(),
                $team->team_name,
                $request->ip()
            );
            return true;
        }
    }
}
