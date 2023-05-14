<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Setting\Repositories\DashboardSettingRepository;

/**
 * Class DashboardSettingsController
 *
 * Dashboard Setting update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Setting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class DashboardSettingsController extends Controller
{
    protected $dashboardSettingRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param DashboardSettingRepository $setting [Object]
     *
     * @return void
     */
    public function __construct(DashboardSettingRepository $setting)
    {
        $this->dashboardSettingRepo = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->dashboardSettingRepo->findAll();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update dashboard setting]
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(24, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->dashboardSettingRepo->create($request)) {
            return response()->json('success', 200);
        } else {
            return response()->json(['error'],400);
        }
    }
}
