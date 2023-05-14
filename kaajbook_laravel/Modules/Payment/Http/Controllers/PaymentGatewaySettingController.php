<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Payment\Repositories\PaymentGatewaySettingRepository;

/**
 * Class PaymentGatewaySettingController
 *
 * Payment Gateway Setting create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Payment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class PaymentGatewaySettingController extends Controller
{
    protected $paymentGatewaySettingRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param PaymentGatewaySettingRepository $settingRepo [Object]
     *
     * @return void
     */
    public function __construct(PaymentGatewaySettingRepository $settingRepo)
    {
        $this->paymentGatewaySettingRepo = $settingRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->paymentGatewaySettingRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create/edit payment gateway setting]
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(35, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->paymentGatewaySettingRepo->create($request)) {
            return response()->json('success', 200);
        } else {
            return response()->json('error', 401);
        }
    }
}
