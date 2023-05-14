<?php

namespace Modules\UserActivity\Repositories;

use Carbon\Carbon;
use Auth;
use Modules\UserActivity\Models\UserActivity;

/**
 * Class UserActivityRepository
 *
 * UserActivity create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\UserActivity
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class UserActivityRepository
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [request object]
     *
     * @return object
     */
    public function getAllActivities($request)
    {
        $user_id = $request->get('userId');
        $userActivity = UserActivity::where('user_id', $user_id);
        $totalData = $userActivity->count();

        $columns = array(
            0 =>'created_at',
            1 =>'module',
            2 =>'description'
        );
        
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $userActivity = $userActivity->where(
                function ($query) use ($search) {
                    $query->where('description', 'LIKE', "%{$search}%")
                        ->orWhere('created_at', 'LIKE', "%{$search}%")
                        ->orWhere('module', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $userActivity->count();
        }

        $userActivity = $userActivity->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        return array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $userActivity
        );
    }

    /**
     * Display a today all activities.
     *
     * @param Request $request [Params for list activity]
     *
     * @return Object
     */
    public function getTodayActivities($request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $matchThese = [['created_at','>=',Carbon::now()->subDay()]];
        $data = $user->userActivity()->with([
                'user' => function ($query) {
                    $query->select('id','firstname','lastname','avatar');
                }
            ])
        ->where($matchThese)
        ->orderBy('id', 'desc');

        if ($request->has('length')) {
            $data = $data->take($request->get('length'));
        }

        return $data->get();
    }

    /**
     * Get activities.
     *
     * @param String $module          [Module name]
     * @param Int    $module_field_id [Module field id]
     *
     * @return Response
     */
    public function getActivities($module, $module_field_id)
    {
        return UserActivity::with([
            'user' => function ($query) {
                $query->select('id', 'firstname', 'lastname', 'avatar');
            }
        ])
        ->where('module', $module)
        ->where('module_field_id', $module_field_id)
        ->orderBy('id', 'desc')
        ->get();
    }
}
