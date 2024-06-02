<?php

namespace Modules\Invoice\Repositories;

use Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Modules\Estimate\Models\Estimate;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceItem;
use Modules\Invoice\Models\InvoiceSetting;
use Modules\Payment\Models\PaymentMethod;
use Modules\Setting\Models\Setting;
use Modules\Tax\Models\Tax;
use Modules\User\Models\User\User;
use Modules\User\Repositories\UserRepository;

/**
 * Class InvoiceRepository
 *
 * Invoice CRUD functionality
 *
 * Invoice create, update, delete and view
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
class InvoiceRepository
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
     * @return Object
     */
    public function findAll()
    {
        return Invoice::All();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get invoice]
     *
     * @return Object
     */
    public function getAllInvoices($request)
    {
        $user = Auth::user();
        $invoices_table = config('core.acl.invoices_table');
        $user_table = config('core.acl.users_table');

        $columns = array(
            0 => $invoices_table . '.invoice_number',
            1 => $user_table . '.firstname',
            2 => $invoices_table . '.invoice_date',
            3 => $invoices_table . '.due_date',
            4 => $invoices_table . '.total_amount',
            5 => $invoices_table . '.status',
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $invoices = Invoice::select(
            $invoices_table . '.*',
            $user_table . '.firstname as client_firstname',
            $user_table . '.lastname as client_lastname',
            $user_table . '.avatar as client_avatar'
        )
            ->leftjoin($user_table, $user_table . '.id', '=', $invoices_table . '.client_id');

        if ($user->is_client) {
            $invoices->where($invoices_table . '.client_id', $user->id);
        } elseif (!$user->hasRole('admin') && !$user->is_super_admin) {
            $invoices->where($invoices_table . '.user_id', $user->id);
        }

        $totalData = $invoices->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $invoices = $invoices->where(
                function ($query) use ($search, $invoices_table, $user_table) {
                    $query->where($invoices_table . '.invoice_number', 'LIKE', "%{$search}%")
                        ->orWhere($invoices_table . '.invoice_date', 'LIKE', "%{$search}%")
                        ->orWhere($invoices_table . '.due_date', 'LIKE', "%{$search}%")
                        ->orWhere($invoices_table . '.total_amount', 'LIKE', "%{$search}%")
                        ->orWhere($invoices_table . '.status', 'LIKE', "%{$search}%")
                        ->orWhere(DB::raw('concat(' . $user_table . '.firstname," ",' . $user_table . '.lastname)'), 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $invoices->count();
        }

        $data = $invoices->offset($start)
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
        return Invoice::with('user', 'client', 'items.taxes', 'payments', 'project')->findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create invoice]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $subTotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;
        $totalAmount = 0;
        $input = $request->all();
        $user = Auth::user();
        $invoice = new Invoice;
        $input['user_id'] = $user->id;
        $input['invoice_number'] = $this->getInvoiceNumber();
        $invoice = $invoice->create($input);
        if ($invoice) {
            $items = $input['items'];
            foreach ($items as $key => $value) {
                $value['total_item_amount'] = $value['unit_price'] * $value['quantity'];
                $invoiceItem = $invoice->items()->create($value);
                $subTotal += $value['total_item_amount'];

                if (isset($value['taxes'])) {
                    foreach ($value['taxes'] as $key1 => $value1) {
                        $tax = Tax::findOrFail($value1);
                        $item_tax = ($invoiceItem->total_item_amount * $tax->tax_rate / 100);
                        $invoiceItem->taxes()->attach($value1, ['item_tax' => $item_tax]);
                        $totalTax += $item_tax;
                    }
                }
            }

            if (isset($input['discount_type'])) {
                if ($input['discount_type'] == 'percent') {
                    $totalDiscount = (($subTotal + $totalTax) * $input['discount']) / 100;
                } elseif ($input['discount_type'] == 'fixed') {
                    $totalDiscount = $input['discount'];
                }
            }

            $totalAmount = $subTotal + $totalTax + $input['adjustment'] - $totalDiscount;

            $invoice->sub_total = $subTotal;
            $invoice->total_tax = $totalTax;
            $invoice->total_discount = $totalDiscount;
            $invoice->total_amount = $totalAmount;
            $invoice->total_due_amount = $totalAmount;
            $invoice->save();

            // Sent invoice to customer.
            $invoiceData = Invoice::with('client', 'items.taxes', 'payments')->find($invoice->id);
            $this->emailsHelper->sendInvoiceEmail($invoiceData);

            ////////////////////////////////////////////////
            $currency = Setting::Currency()->first();
            $inv_url = config('app.front_url').'/#/invoices/detail/'.$invoice->id;
            $previousDueAmount_this_client = Invoice::where('client_id', $invoiceData->client_id)
            ->sum('total_due_amount');
            $previousDueAmount_this_client = $previousDueAmount_this_client - $invoiceData->total_due_amount;

            $client = new Client();
            $url = 'https://labapi.smlbulksms.com/smsapiv3';
            $apiKey = '58773bd2a25ed60b982a04335b77307c';
            $sender = '8809617649928';
            $msisdn = $invoiceData->client->mobile;
//             $message = 'Dear '.$invoiceData->client->full_name. '
// We have created an invoice "'.$invoiceData->invoice_number.'" for you in the amount of '.$currency->symbol.' '. $invoiceData->total_amount.'. 
// You can view the invoice on the following link: '. $inv_url.
// ' Best Regards,
// The kaajbook Team.';
            $message = 'New Job bill : '.$currency->symbol.' '. $invoiceData->total_amount.'
Previous due : '.$currency->symbol.' '.$previousDueAmount_this_client.'
Click Link : '.$inv_url;
    
            try {   
                $response = $client->get($url, [
                    'query' => [
                        'apikey' => $apiKey,
                        'sender' => $sender,
                        'msisdn' => $msisdn,
                        'smstext' => $message,
                    ],
                ]);
    
                return response()->json(['status' => 'success', 'sms_response' => $response->getBody()->getContents()]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
            ////////////////////////////////////////////////

            // Add Notification.
            $admins = UserRepository::getAdminUsers();
            createNotification($admins, 'invoices', $invoice->id, 'Invoice Created.', "#" . $invoice->invoice_number);
            createNotification([$invoice->client], 'invoices', $invoice->id, 'Invoice Created.', "#" . $invoice->invoice_number);

            // --
            // Add activities
            createUserActivity(
                Invoice::MODULE_NAME,
                $invoice->id,
                $request->method(),
                $invoice->invoice_number,
                $request->ip()
            );            
            return true;
        }
        return false;
    }

    /**
     * Convert estimate to invoice on estimate accept.
     *
     * @param Object $estimate [Estimate object]
     *
     * @return boolean
     */
    public function convretEstimateToInvoice($estimate)
    {
        $invoiceSetting = InvoiceSetting::first();
        $invoice = new Invoice;
        $input['user_id'] = $estimate->user_id;
        $input['client_id'] = $estimate->client_id;
        $input['estimate_id'] = $estimate->id;
        $input['invoice_number'] = $this->getInvoiceNumber();
        $input['invoice_date'] = date('Y-m-d');
        $input['due_date'] = date('Y-m-d', strtotime('+' . $invoiceSetting->due_after . ' days'));
        $input['sub_total'] = $estimate->sub_total;
        $input['total_tax'] = $estimate->total_tax;
        $input['discount_type'] = $estimate->discount_type;
        $input['discount'] = $estimate->discount;
        $input['total_discount'] = $estimate->total_discount;
        $input['adjustment'] = $estimate->adjustment;
        $input['total_amount'] = $estimate->total_amount;
        $input['total_due_amount'] = $estimate->total_amount;
        $input['reference'] = $estimate->reference;
        $input['note'] = $estimate->note;
        $invoice = $invoice->create($input);
        if ($invoice) {
            foreach ($estimate->items as $key => $value) {
                $invoiceItem = $invoice->items()->create(
                    [
                        "invoice_id" => $invoice->id,
                        "item_name" => $value->item_name,
                        "item_description" => $value->item_description,
                        "item_unit" => $value->item_unit,
                        "unit_price" => $value->unit_price,
                        "quantity" => $value->quantity,
                        "total_item_amount" => $value->total_item_amount,
                    ]
                );

                foreach ($value->taxes as $key1 => $value1) {
                    $invoiceItem->taxes()->attach($value1, ['item_tax' => $value1->pivot->item_tax]);
                }
            }

            // Sent invoice to customer.
            $invoiceData = Invoice::with('client', 'items.taxes', 'payments')->find($invoice->id);
            $this->emailsHelper->sendInvoiceEmail($invoiceData);

            // Add Notification.
            $admins = UserRepository::getAdminUsers();
            createNotification($admins, 'invoices', $invoice->id, 'Invoice Created.', "#" . $invoice->invoice_number);
            createNotification([$invoice->client], 'invoices', $invoice->id, 'Invoice Created.', "#" . $invoice->invoice_number);

            // Update estimate.
            Estimate::where('id', $estimate->id)->update(['invoice_id' => $invoice->id]);

            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update invoice]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function update($request, $id)
    {
        $subTotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;
        $totalAmount = 0;
        $invoice = Invoice::findOrFail($id);
        $input = $request->all();
        $paidAmount = $invoice->total_amount - $invoice->total_due_amount;

        if ($invoice->update($input)) {
            // Delete invoice item.
            InvoiceItem::where('invoice_id', $invoice->id)->delete();

            $items = $input['items'];
            foreach ($items as $key => $value) {
                $value['total_item_amount'] = $value['unit_price'] * $value['quantity'];
                $invoiceItem = $invoice->items()->create($value);
                $subTotal += $value['total_item_amount'];

                if (isset($value['taxes'])) {
                    foreach ($value['taxes'] as $key1 => $value1) {
                        $tax = Tax::findOrFail($value1);
                        $item_tax = ($invoiceItem->total_item_amount * $tax->tax_rate / 100);
                        $invoiceItem->taxes()->attach($value1, ['item_tax' => $item_tax]);
                        $totalTax += $item_tax;
                    }
                }
            }

            if (isset($input['discount_type'])) {
                if ($input['discount_type'] == 'percent') {
                    $totalDiscount = (($subTotal + $totalTax) * $input['discount']) / 100;
                } elseif ($input['discount_type'] == 'fixed') {
                    $totalDiscount = $input['discount'];
                }
            }

            $totalAmount = $subTotal + $totalTax + $input['adjustment'] - $totalDiscount;
            $totalDueAmount = $totalAmount - $paidAmount;

            $invoice->sub_total = $subTotal;
            $invoice->total_tax = $totalTax;
            $invoice->total_discount = $totalDiscount;
            $invoice->total_amount = $totalAmount;
            $invoice->total_due_amount = $totalDueAmount;
            if ($totalDueAmount <= 0) {
                $invoice->status = 'paid';
            } elseif ($totalDueAmount < $totalAmount) {
                $invoice->status = 'partially_paid';
            } else {
                $invoice->status = 'unpaid';
            }
            $invoice->save();

            // Sent invoice to customer.
            $invoiceData = Invoice::with('client', 'items.taxes', 'payments')->find($invoice->id);
            $this->emailsHelper->sendInvoiceEmail($invoiceData);

            // Add Notification.
            $admins = UserRepository::getAdminUsers();
            createNotification($admins, 'invoices', $invoice->id, 'Invoice Updated.', "#" . $invoice->invoice_number);
            createNotification([$invoice->client], 'invoices', $invoice->id, 'Invoice Updated.', "#" . $invoice->invoice_number);

            // --
            // Add activities
            createUserActivity(
                Invoice::MODULE_NAME,
                $invoice->id,
                $request->method(),
                $invoice->invoice_number,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete invoice]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->delete()) {
            // --
            // Add activities
            createUserActivity(
                Invoice::MODULE_NAME,
                $invoice->id,
                $request->method(),
                $invoice->invoice_number,
                $request->ip()
            );

            return true;
        }
        return false;
    }

    /**
     * Change invoice status.
     *
     * @param Request $request [Request for change invoice status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeInvoiceStatus($request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice) {
            $input['status'] = $request->get('status');
            if ($invoice->update($input)) {
                // --
                // Add activities
                createUserActivity(
                    Invoice::MODULE_NAME,
                    $invoice->id,
                    $request->method(),
                    $invoice->invoice_number,
                    $request->ip(),
                    $invoice->id,
                    true
                );
            }

            return true;
        }
        return false;
    }

    /**
     * Download invoice PDF.
     *
     * @param Int $id [Row id]
     *
     * @return Response
     */
    public function download($id)
    {
        $invoice = Invoice::with('client', 'items.taxes', 'payments')->findOrFail($id);
        $currency = Setting::Currency()->first();
        $setting = Setting::select('company_name', 'company_address', 'company_phone', 'php_date_format', 'php_date_time_format')->first();
        $invoiceSetting = InvoiceSetting::first();
        $paymentMethods = PaymentMethod::where('status', 1)->where('show_on_pdf', 1)->get();

        \View::addLocation(base_path() . '/Modules/Invoice/Resources/views');
        $pdf = \PDF::loadView('invoice-1', compact('invoice', 'currency', 'setting', 'invoiceSetting', 'paymentMethods'));
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Check invoice permission.
     *
     * @param Int $id       [Row id]
     * @param Int $clientId [User/Client id]
     *
     * @return Response
     */
    public function checkPermission($id, $clientId)
    {
        $invoice = Invoice::where('id', $id)
            ->where('client_id', $clientId)
            ->exists();

        if ($invoice) {
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
    public function getInvoiceNumber()
    {
        $invoice_prefix = InvoiceSetting::pluck('invoice_prefix')->first();
        return $invoice_prefix . str_pad(nextAutoID(config('core.acl.invoices_table')), 6, "0", STR_PAD_LEFT);
    }
}
