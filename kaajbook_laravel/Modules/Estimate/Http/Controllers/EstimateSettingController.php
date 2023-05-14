<?php

namespace Modules\Estimate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Estimate\Http\Requests\EstimateSettingRequest;
use Modules\Estimate\Repositories\EstimateSettingRepository;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class EstimateSettingController
 *
 * Estimate Setting create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Estimate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class EstimateSettingController extends Controller
{
    protected $estimateSettingRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param EstimateSettingRepository $settingRepo [Object]
     *
     * @return void
     */
    public function __construct(EstimateSettingRepository $settingRepo)
    {
        $this->estimateSettingRepo = $settingRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->estimateSettingRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EstimateSettingRequest $request [Request for create/edit estimate setting]
     *
     * @return Response
     */
    public function store(EstimateSettingRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(32, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->estimateSettingRepo->create($request)) {
            return response()->json('success', 200);
        } else {
            return response()->json('error', 401);
        }
    }
}
