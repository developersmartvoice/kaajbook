<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Payment\Http\Requests\CreatePaymentMethodRequest;
use Modules\Payment\Http\Requests\UpdatePaymentMethodRequest;
use Modules\Payment\Repositories\PaymentMethodRepository;

/**
 * Class PaymentMethodController
 *
 * Payment method CRUD functionality.
 *
 * Payment method create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\Payment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class PaymentMethodController extends Controller
{
    protected $paymentMethodRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param paymentMethodRepo $paymentMethod [Object]
     *
     * @return void
     */
    public function __construct(PaymentMethodRepository $paymentMethod)
    {
        $this->paymentMethodRepo = $paymentMethod;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->paymentMethodRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get payment methods]
     *
     * @return Response
     */
    public function getAllPaymentMethods(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(34, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->paymentMethodRepo->getAllPaymentMethods($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePaymentMethodRequest $request [Params for create payment method]
     *
     * @return Response
     */
    public function store(CreatePaymentMethodRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(34, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->paymentMethodRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
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
        if (!AdminHelper::can_action(34, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->paymentMethodRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePaymentMethodRequest $request [Params for update payment method]
     * @param int                        $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdatePaymentMethodRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(34, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->paymentMethodRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for destroy payment method]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(34, 'deleted')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->paymentMethodRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
}
