<?php

namespace Modules\Invoice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Invoice\Http\Requests\InvoiceSettingRequest;
use Modules\Invoice\Repositories\InvoiceSettingRepository;

/**
 * Class InvoiceSettingController
 *
 * Invoice Setting create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Invoice
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class InvoiceSettingController extends Controller
{
    protected $invoiceSettingRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param InvoiceSettingRepository $settingRepo [Object]
     *
     * @return void
     */
    public function __construct(InvoiceSettingRepository $settingRepo)
    {
        $this->invoiceSettingRepo = $settingRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->invoiceSettingRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InvoiceSettingRequest $request [Request for create/edit invoice setting]
     *
     * @return Response
     */
    public function store(InvoiceSettingRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(33, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->invoiceSettingRepo->create($request)) {
            return response()->json('success', 200);
        } else {
            return response()->json('error', 401);
        }
    }
}
