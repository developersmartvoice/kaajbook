<?php

namespace Modules\Payment\Repositories;

use Auth;
use Modules\Payment\Models\PaymentGatewaySetting;

/**
 * Class PaymentGatewaySettingRepository
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
class PaymentGatewaySettingRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return object
     */
    public function findAll()
    {
        return PaymentGatewaySetting::first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create/update payment gateway setting]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $user = Auth::user();
        $paymentGatewaySetting = PaymentGatewaySetting::first();
        $input = $request->all();
        if ($paymentGatewaySetting->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                PaymentGatewaySetting::MODULE_NAME,
                $paymentGatewaySetting->id,
                'PUT',
                $user->full_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }
}
