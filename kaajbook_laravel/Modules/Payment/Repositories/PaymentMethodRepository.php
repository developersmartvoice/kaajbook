<?php

namespace Modules\Payment\Repositories;

use Modules\Payment\Models\PaymentMethod;

/**
 * Class PaymentMethodRepository
 *
 * Payment method CRUD functionality
 *
 * Payment method create, update, delete and view
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
class PaymentMethodRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return PaymentMethod::where('status', 1)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get payment methods]
     *
     * @return Object
     */
    public function getAllPaymentMethods($request)
    {
        $columns = array(
            0 => 'method_name',
            1 => 'description',
            2 => 'status'
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $paymentMethods = new PaymentMethod();

        $totalData = $paymentMethods->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $paymentMethods = $paymentMethods->where(
                function ($query) use ($search) {
                    $query->where('method_name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $paymentMethods->count();
        }

        $data = $paymentMethods->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        return array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
    }

    /**
     * Display a payment method of the resource.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return PaymentMethod::findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create payment method]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $paymentMethod = new PaymentMethod;
        if ($paymentMethod->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                PaymentMethod::MODULE_NAME,
                $paymentMethod->id,
                $request->method(),
                $paymentMethod->method_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update payment method]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $paymentMethod = PaymentMethod::findOrFail($id);
        if ($paymentMethod->fill($input)->save()) {
            // --
            // Add activities
            createUserActivity(
                PaymentMethod::MODULE_NAME,
                $paymentMethod->id,
                $request->method(),
                $paymentMethod->method_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete payment method]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        if ($paymentMethod->delete()) {
            // --
            // Add activities
            createUserActivity(
                PaymentMethod::MODULE_NAME,
                $paymentMethod->id,
                $request->method(),
                $paymentMethod->method_name,
                $request->ip()
            );
            return true;
        }
        return false;
    }
}
