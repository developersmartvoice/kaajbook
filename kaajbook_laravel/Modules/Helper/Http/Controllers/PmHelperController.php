<?php

namespace Modules\Helper\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Helper\Repositories\PmHelperRepository;
use Modules\Projects\Models\Project;
use Modules\User\Models\User\User;

/**
 * Class PmHelperController
 *
 * PM Helper functions
 *
 * PHP version 7.1.3
 *
 * @category  Helper
 * @package   Modules\Helper
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class PmHelperController extends Controller
{
    protected $pmHelperRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param HelperRepository $helper [Object]
     *
     * @return void
     */
    public function __construct(PmHelperRepository $helper)
    {
        $this->pmHelperRepo = $helper;
    }

    /**
     * Get data for dashboard.
     *
     * @param Request $request [Request for get dashboard data]
     *
     * @return Response
     */
    public function getDashboardData(Request $request)
    {
        // --
        // Check role/permission
        // if (!AdminHelper::can_action(1, 'view')) {
        //     return response()->json("Access denied", 403);
        // }

        return $this->pmHelperRepo->getDashboardData($request);
    }

    /**
     * Check user permission.
     *
     * @param Int    $id     [Project Id]
     * @param String $action [Action Name]
     *
     * @return void
     */
    public static function canUserAction($id, $action)
    {
        $data = Project::checkPermission($id, Auth::user()->id, $action)
            ->where('id', $id)
            ->firstOrFail();

        if ($data->users->isEmpty()) {
            return false;
        } else {
            return true;
        }
    }
}
