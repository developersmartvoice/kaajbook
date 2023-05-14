<?php

namespace Modules\Payment\Repositories;

use Auth;
use Illuminate\Support\Facades\DB;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceSetting;
use Modules\Payment\Models\Payment;
use Modules\Setting\Models\Setting;
use Modules\User\Models\User\User;
use Modules\User\Repositories\UserRepository;

/**
 * Class PaymentRepository
 *
 * Payment CRUD functionality
 *
 * Payment create, update, delete and view
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
class PaymentRepository
{
    protected $emailsHelper;

    /**
     * Instantiate a new helper instance.
     *
     * @param EmailsHelper $emailsHelper [Object]
     *
     * @return void
     */
    public function __construct(EmailsHelper $emailsHelper)
    {
        $this->emailsHelper = $emailsHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get payment]
     *
     * @return Object
     */
    public function getAllPayments($request)
    {
        $user = Auth::user();
        $payments_table = config('core.acl.payments_table');
        $invoices_table = config('core.acl.invoices_table');
        $user_table = config('core.acl.users_table');

        $columns = array(
            0 => $payments_table . '.receipt_number',
            1 => $invoices_table . '.invoice_number',
            2 => $user_table . '.firstname',
            3 => $payments_table . '.date',
            4 => $payments_table . '.amount',
            5 => $payments_table . '.payment_method',
            6 => $payments_table . '.status',
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $payments = Payment::select(
            $payments_table . '.*',
            $invoices_table . '.invoice_number',
            $user_table . '.firstname as client_firstname',
            $user_table . '.lastname as client_lastname',
            $user_table . '.avatar as client_avatar'
        )
            ->join($user_table, $user_table . '.id', '=', $payments_table . '.client_id')
            ->join($invoices_table, $invoices_table . '.id', '=', $payments_table . '.invoice_id');

        if ($user->is_client) {
            $payments->where($payments_table . '.client_id', $user->id);
        } elseif (!$user->hasRole('admin') && !$user->is_super_admin) {
            $payments->where($payments_table . '.user_id', $user->id);
        }

        $totalData = $payments->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $payments = $payments->where(
                function ($query) use ($search, $payments_table, $user_table, $invoices_table) {
                    $query->where($payments_table . '.receipt_number', 'LIKE', "%{$search}%")
                        ->orWhere($payments_table . '.date', 'LIKE', "%{$search}%")
                        ->orWhere($payments_table . '.amount', 'LIKE', "%{$search}%")
                        ->orWhere($payments_table . '.payment_method', 'LIKE', "%{$search}%")
                        ->orWhere($payments_table . '.status', 'LIKE', "%{$search}%")
                        ->orWhere($invoices_table . '.invoice_number', 'LIKE', "%{$search}%")
                        ->orWhere(DB::raw('concat(' . $user_table . '.firstname," ",' . $user_table . '.lastname)'), 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $payments->count();
        }

        $data = $payments->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $settings = Setting::Currency()->first();

        return array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "settings" => $settings,
        );
    }

    /**
     * Retrive particular resource.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return Payment::with('client', 'invoice')->findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create payment]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $user = Auth::user();
        $payment = new Payment;
        $input = $request->all();
        $input['user_id'] = $user->id;
        $input['receipt_number'] = $this->_getReceiptNumber();
        $input['date'] = date('Y-m-d H:i:s');
        $payment = $payment->create($input);
        if ($payment) {
            $paymentData = Payment::with('client', 'invoice.user')->findOrFail($payment->id);
            if ($paymentData->status == 'successful') {
                // Update invoice data.
                $invoice = Invoice::findOrFail($paymentData->invoice_id);
                $invoice->total_due_amount = $invoice->total_due_amount - $paymentData->amount;
                if ($invoice->total_due_amount == 0) {
                    $invoice->status = 'paid';
                } else {
                    $invoice->status = 'partially_paid';
                }
                $invoice->save();

                // Sent payment receipt to customer.
                $this->emailsHelper->sendPaymentEmailToCustomer($paymentData);
                $this->emailsHelper->sendPaymentEmailToStaff($paymentData);

                // Add Notification.
                $this->_createNotification($paymentData, $invoice, 'Payment Successfull');
            }

            // --
            // Add activities
            createUserActivity(
                Payment::MODULE_NAME,
                $paymentData->id,
                $request->method(),
                $paymentData->receipt_number,
                $request->ip()
            );

            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update payment]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $payment = Payment::findOrFail($id);
        $input = $request->all();
        $input['date'] = date('Y-m-d H:i:s');

        $invoice = Invoice::findOrFail($payment->invoice_id);
        $invoice->total_due_amount = $invoice->total_due_amount + $payment->amount - $input['amount'];

        if ($payment->update($input)) {
            // Update invoice data.
            if ($payment->status == 'successful') {
                if ($invoice->total_due_amount == 0) {
                    $invoice->status = 'paid';
                } else {
                    $invoice->status = 'partially_paid';
                }
                $invoice->save();

                // Add Notification.
                $this->_createNotification($payment, $invoice, 'Payment Updated');
            }

            // --
            // Add activities
            createUserActivity(
                Payment::MODULE_NAME,
                $payment->id,
                $request->method(),
                $payment->receipt_number,
                $request->ip()
            );

            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete payment]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $payment = Payment::findOrFail($id);

        $invoice = Invoice::findOrFail($payment->invoice_id);
        if ($payment->status = 'successful') {
            // Update invoice data.
            $invoice->total_due_amount = $invoice->total_due_amount + $payment->amount;
            if ($invoice->total_due_amount == $invoice->total_amount) {
                $invoice->status = 'unpaid';
            } else {
                $invoice->status = 'partially_paid';
            }
        }
        if ($payment->delete()) {

            $invoice->save();

            // --
            // Add activities
            createUserActivity(
                Payment::MODULE_NAME,
                $payment->id,
                $request->method(),
                $payment->receipt_number,
                $request->ip()
            );

            // Add Notification.
            $this->_createNotification($payment, $invoice, 'Payment Deleted.', $request->method());

            return true;
        }
        return false;
    }

    /**
     * Download payment receipt.
     *
     * @param Int $id [Row id]
     *
     * @return Response
     */
    public function download($id)
    {
        $payment = Payment::with('client', 'invoice')->findOrFail($id);
        $currency = Setting::Currency()->first();
        $setting = Setting::select('company_name', 'company_address', 'company_phone', 'php_date_time_format')->first();
        $invoiceSetting = InvoiceSetting::first();

        \View::addLocation(base_path() . '/Modules/Payment/Resources/views');
        $pdf = \PDF::loadView('payment-1', compact('payment', 'currency', 'setting', 'invoiceSetting'));
        return $pdf->download($payment->receipt_number . '.pdf');
    }

    /**
     * Check payment permission.
     *
     * @param Int $id       [Row id]
     * @param Int $clientId [User/Client id]
     *
     * @return Response
     */
    public function checkPermission($id, $clientId)
    {
        $payment = Payment::where('id', $id)
            ->where('client_id', $clientId)
            ->exists();

        if ($payment) {
            return true;
        } else {

            $user = User::findOrFail($clientId);
            if ($user->hasRole('admin') || $user->is_super_admin) {
                return true;
            }

            return false;
        }
    }

    /**
     * Retrive generated id.
     *
     * @return String
     */
    private function _getReceiptNumber()
    {
        $payment_prefix = InvoiceSetting::pluck('payment_prefix')->first();
        return $payment_prefix . str_pad(nextAutoID(config('core.acl.payments_table')), 6, "0", STR_PAD_LEFT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request      [Request for create payment]
     * @param Array   $paymentArray [Payment Array]
     *
     * @return Boolean
     */
    public function stripePaymentCharge($request, $paymentArray = [])
    {
        $user = Auth::user();
        $input = $paymentArray;
        $payment = new Payment;
        $input['user_id'] = $user->id;
        $input['receipt_number'] = $this->_getReceiptNumber();
        $input['date'] = date('Y-m-d H:i:s');
        $payment = $payment->create($input);

        if ($payment) {
            $paymentData = Payment::with('client', 'invoice.user')->findOrFail($payment->id);
            if ($paymentData->status == 'successful') {

                // --
                // Update invoice data.
                $invoice = Invoice::findOrFail($paymentData->invoice_id);
                $invoice->total_due_amount = $invoice->total_due_amount - $paymentData->amount;

                if ($invoice->total_due_amount == 0) {
                    $invoice->status = 'paid';
                } else {
                    $invoice->status = 'partially_paid';
                }
                $invoice->save();

                // Sent payment receipt to customer.
                $this->emailsHelper->sendPaymentEmailToCustomer($paymentData);
                $this->emailsHelper->sendPaymentEmailToStaff($paymentData);

                // Add Notification.
                $this->_createNotification($paymentData, $invoice, 'Payment Successfull');
            }

            // --
            // Add activities
            createUserActivity(
                Payment::MODULE_NAME,
                $paymentData->id,
                $request->method(),
                $paymentData->receipt_number,
                $request->ip()
            );

            return true;
        }
        return false;
    }

    /**
     * Create notification.
     *
     * @param Object $payment [Payment Object]
     * @param Object $invoice [Invoice Object]
     * @param String $title   [Title]
     * @param String $method  [Method]
     *
     * @return Boolean
     */
    private function _createNotification($payment, $invoice, $title, $method = null)
    {
        $admins = UserRepository::getAdminUsers();
        $admins->push($invoice->user);
        $admins->push($invoice->client);
        $admins = $admins->unique();
        createNotification($admins, 'payments', $payment->id, $title, "#" . $payment->receipt_number, $method);
    }
}
