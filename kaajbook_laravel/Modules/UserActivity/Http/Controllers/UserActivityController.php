<?php

namespace Modules\UserActivity\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\UserActivity\Repositories\UserActivityRepository;

/**
 * Class UserActivityController
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
class UserActivityController extends Controller
{
    protected $useractivityRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param UserActivityRepository $useractivity [Object]
     *
     * @return void
     */
    public function __construct(UserActivityRepository $useractivity)
    {
        $this->useractivityRepo = $useractivity;
    }

    /**
     * Display All Activities.
     *
     * @param Request $request [Object]
     *
     * @return void
     */
    public function getAllActivities(Request $request)
    {
        return $this->useractivityRepo->getAllActivities($request);
    }

    /**
     * Display a today all activities.
     *
     * @param Request $rerquest [Params for list activity]
     *
     * @return Response
     */
    public function getTodayActivities(Request $rerquest)
    {
        return $this->useractivityRepo->getTodayActivities($rerquest);
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
        return $this->useractivityRepo->getActivities(
            $module,
            $module_field_id
        );

        // return response()->json(compact('activities'));
    }
}
