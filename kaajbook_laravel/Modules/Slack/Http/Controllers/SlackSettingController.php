<?php

namespace Modules\Slack\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Slack\Repositories\SlackSettingRepository;

/**
 * Class SlackSettingController
 *
 * Slack Setting create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Slack
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class SlackSettingController extends Controller
{
    protected $slackSettingRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param SlackSettingRepository $settingRepo [Object]
     *
     * @return void
     */
    public function __construct(SlackSettingRepository $settingRepo)
    {
        $this->slackSettingRepo = $settingRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->slackSettingRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create/edit slack setting]
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(36, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->slackSettingRepo->create($request)) {
            return response()->json('success', 200);
        } else {
            return response()->json('error', 401);
        }
    }
}
