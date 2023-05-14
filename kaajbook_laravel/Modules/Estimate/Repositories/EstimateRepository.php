<?php

namespace Modules\Estimate\Repositories;

use Auth;
use Illuminate\Support\Facades\DB;
use Modules\Estimate\Models\Estimate;
use Modules\Estimate\Models\EstimateItem;
use Modules\Estimate\Models\EstimateSetting;
use Modules\Helper\Helpers\EmailsHelper;
use Modules\Setting\Models\Setting;
use Modules\Tax\Models\Tax;
use Modules\User\Models\User\User;
use Modules\Invoice\Repositories\InvoiceRepository;
use Modules\User\Repositories\UserRepository;

/**
 * Class EstimateRepository
 *
 * Estimate CRUD functionality
 *
 * Estimate create, update, delete and view
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
class EstimateRepository
{
    protected $emailsHelper;
    protected $invoiceRepo;

    /**
     * Instantiate a new helper instance.
     *
     * @param EmailsHelper $emailsHelper [Object]
     * @param InvoiceRepository $invoiceRepo [Object]
     *
     * @return void
     */
    public function __construct(EmailsHelper $emailsHelper, InvoiceRepository $invoiceRepo)
    {
        $this->emailsHelper = $emailsHelper;
        $this->invoiceRepo = $invoiceRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function findAll()
    {
        return Estimate::All();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get estimate]
     *
     * @return Object
     */
    public function getAllEstimates($request)
    {
        $user = Auth::user();
        $estimates_table = config('core.acl.estimates_table');
        $user_table = config('core.acl.users_table');

        $columns = array(
            0 => $estimates_table.'.estimate_number',
            1 => $user_table.'.firstname',
            2 => $estimates_table.'.estimate_date',
            3 => $estimates_table.'.valid_till',
            4 => $estimates_table.'.total_amount',
            5 => $estimates_table.'.status'
        );

        $input = $request->input();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $estimates = Estimate::select(
            $estimates_table.'.*',
            $user_table.'.firstname as client_firstname',
            $user_table.'.lastname as client_lastname',
            $user_table.'.avatar as client_avatar'
        )
        ->leftjoin($user_table, $user_table.'.id', '=', $estimates_table.'.client_id');

        if ($user->is_client) {
            $estimates->where($estimates_table.'.client_id', $user->id)->whereNotIn($estimates_table.'.status', ['draft']);
        } elseif (!$user->hasRole('admin') && !$user->is_super_admin) {
            $estimates->where($estimates_table.'.user_id', $user->id);
        }

        $totalData = $estimates->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $estimates = $estimates->where(
                function ($query) use ($search, $estimates_table, $user_table) {
                    $query->where($estimates_table.'.estimate_number', 'LIKE', "%{$search}%")
                        ->orWhere($estimates_table.'.total_amount', 'LIKE', "%{$search}%")
                        ->orWhere($estimates_table.'.estimate_date', 'LIKE', "%{$search}%")
                        ->orWhere($estimates_table.'.valid_till', 'LIKE', "%{$search}%")
                        ->orWhere($estimates_table.'.status', 'LIKE', "%{$search}%")
                        ->orWhere(DB::raw('concat('.$user_table.'.firstname," ",'.$user_table.'.lastname)'), 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $estimates->count();
        }

        $data = $estimates->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $settings = Setting::Currency()->first();

        return array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
            "settings"        => $settings
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
        return Estimate::with('user', 'client', 'items.taxes')->findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create estimate]
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
        $estimate = new Estimate;
        $input['user_id'] = $user->id;
        $input['estimate_number'] = $this->_getEstimateNumber();
        $estimate = $estimate->create($input);
        if ($estimate) {
            $items = $input['items'];
            foreach ($items as $key => $value) {
                $value['total_item_amount'] = $value['unit_price'] * $value['quantity'];
                $estimateItem = $estimate->items()->create($value);
                $subTotal += $value['total_item_amount'];

                if(isset($value['taxes'])) {
                    foreach ($value['taxes'] as $key1 => $value1) {
                        $tax = Tax::findOrFail($value1);
                        $item_tax = ($estimateItem->total_item_amount * $tax->tax_rate / 100);
                        $estimateItem->taxes()->attach($value1, [ 'item_tax' => $item_tax ]);
                        $totalTax += $item_tax;
                    }
                }
            }

            if (isset($input['discount_type'])) {
                if ($input['discount_type'] == 'percent') {
                    $totalDiscount = ( ($subTotal + $totalTax) * $input['discount'] ) / 100;
                }elseif ($input['discount_type'] == 'fixed') {
                    $totalDiscount = $input['discount'];
                }
            }

            $totalAmount = $subTotal + $totalTax + $input['adjustment'] - $totalDiscount;

            $estimate->sub_total = $subTotal;
            $estimate->total_tax = $totalTax;
            $estimate->total_discount = $totalDiscount;
            $estimate->total_amount = $totalAmount;
            $estimate->save();            

            // Sent estimate to customer.
            if ($estimate->status == 'sent') {
                $estimateData = Estimate::with('client', 'items.taxes')->find($estimate->id);
                $this->emailsHelper->sendEstimateEmail($estimateData);

                // Add Notification.
                $admins = UserRepository::getAdminUsers();
                createNotification($admins, 'estimates', $estimate->id, 'Estimate Created.',  "#" . $estimate->estimate_number);
                createNotification([$estimate->client], 'estimates', $estimate->id, 'Estimate Created.',  "#" . $estimate->estimate_number);
            }

            // --
            // Add activities
            createUserActivity(
                Estimate::MODULE_NAME,
                $estimate->id,
                $request->method(),
                $estimate->estimate_number,
                $request->ip()
            );

            return true;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update estimate]
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
        $estimate = Estimate::findOrFail($id);
        $oldStatus = $estimate->status;
        $input = $request->all();
        if ($estimate->update($input)) {
            // Delete estimate item.
            EstimateItem::where('estimate_id', $estimate->id)->delete();

            $items = $input['items'];
            foreach ($items as $key => $value) {
                $value['total_item_amount'] = $value['unit_price'] * $value['quantity'];
                $estimateItem = $estimate->items()->create($value);
                $subTotal += $value['total_item_amount'];

                if(isset($value['taxes'])) {
                    foreach ($value['taxes'] as $key1 => $value1) {
                        $tax = Tax::findOrFail($value1);
                        $item_tax = ($estimateItem->total_item_amount * $tax->tax_rate / 100);
                        $estimateItem->taxes()->attach($value1, [ 'item_tax' => $item_tax ]);
                        $totalTax += $item_tax;
                    }
                }
            }

            if (isset($input['discount_type'])) {
                if ($input['discount_type'] == 'percent') {
                    $totalDiscount = ( ($subTotal + $totalTax) * $input['discount'] ) / 100;
                }elseif ($input['discount_type'] == 'fixed') {
                    $totalDiscount = $input['discount'];
                }
            }

            $totalAmount = $subTotal + $totalTax + $input['adjustment'] - $totalDiscount;

            $estimate->sub_total = $subTotal;
            $estimate->total_tax = $totalTax;
            $estimate->total_discount = $totalDiscount;
            $estimate->total_amount = $totalAmount;
            $estimate->save();            

            $estimateData = Estimate::with('user', 'client', 'items.taxes')->find($estimate->id);
            if ($estimate->status == 'sent') {
                $this->emailsHelper->sendEstimateEmail($estimateData);
                // Add Notification.
                $admins = UserRepository::getAdminUsers();
                createNotification($admins, 'estimates', $estimate->id, 'Estimate Updated.',  "#" . $estimate->estimate_number);
                createNotification([$estimate->client], 'estimates', $estimate->id, 'Estimate Updated.',  "#" . $estimate->estimate_number);
            }elseif ($oldStatus != 'declined' && $estimate->status == 'declined') {
                $this->emailsHelper->sendEstimateDeclinedEmail($estimateData);
            } elseif ($oldStatus != 'accepted' && $estimate->status == 'accepted') {
                $this->emailsHelper->sendEstimateAcceptedEmail($estimateData);
                $this->emailsHelper->sendEstimateThankYouEmail($estimateData);
            }

            // --
            // Add activities
            createUserActivity(
                Estimate::MODULE_NAME,
                $estimate->id,
                $request->method(),
                $estimate->estimate_number,
                $request->ip()
            );
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete estimate]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $estimate = Estimate::findOrFail($id);
        if ($estimate->delete()) {
            // --
            // Add activities
            createUserActivity(
                Estimate::MODULE_NAME,
                $estimate->id,
                $request->method(),
                $estimate->estimate_number,
                $request->ip()
            );

            return true;
        }
        return false;
    }

    /**
     * Change estimate status.
     *
     * @param Request $request [Request for change estimate status]
     * @param Int     $id      [Row id]
     *
     * @return Object
     */
    public function changeEstimateStatus($request, $id)
    {
        $estimate = Estimate::with('user', 'client', 'items.taxes')->findOrFail($id);
        $oldStatus = $estimate->status;
        if ($estimate) {
            $input['status'] = $request->get('status');
            if ($estimate->update($input)) {

                $admins = UserRepository::getAdminUsers();
                $admins = $admins->push($estimate->user)->unique();

                if ($oldStatus != 'sent' && $estimate->status == 'sent') {
                    $this->emailsHelper->sendEstimateEmail($estimate);
                } elseif ($oldStatus != 'declined' && $estimate->status == 'declined') {
                    $this->emailsHelper->sendEstimateDeclinedEmail($estimate);
                    // Add Notification.
                    createNotification($admins, 'estimates', $estimate->id, 'Estimate Declined.',  "#" . $estimate->estimate_number);
                } elseif ($oldStatus != 'accepted' && $estimate->status == 'accepted') {
                    $this->emailsHelper->sendEstimateAcceptedEmail($estimate);
                    $this->emailsHelper->sendEstimateThankYouEmail($estimate);
                    // Add Notification.
                    createNotification($admins, 'estimates', $estimate->id, 'Estimate Accepted.',  "#" . $estimate->estimate_number);
                    // Convert estimate to invoice.
                    $this->_convretEstimateToInvoice($estimate);
                }

                // --
                // Add activities
                createUserActivity(
                    Estimate::MODULE_NAME,
                    $estimate->id,
                    $request->method(),
                    $estimate->estimate_number,
                    $request->ip(),
                    $estimate->id,
                    true
                );
            }
            
            return true;
        }
        return false;
    }

    /**
     * Convert estimate to invoice on estimate accept.
     *
     * @param Object $estimate   [Estimate object]
     *
     * @return boolean
     */
    private function _convretEstimateToInvoice($estimate)
    {
        $estimateSetting = EstimateSetting::first();
        if ($estimateSetting->auto_convert_to_invoice_on_client_accept) {
            return $this->invoiceRepo->convretEstimateToInvoice($estimate);
        }
        return true;
    }

    /**
     * Download estimate PDF.
     *
     * @param Int $id [Row id]
     *
     * @return Response
     */
    public function download($id)
    {
        // return response()->download(public_path('uploads').'/EST-000001.pdf');
        $estimate = Estimate::with('client', 'items.taxes')->find($id);
        $currency = Setting::Currency()->first();
        $setting = Setting::select('company_name', 'company_address', 'company_phone',  'php_date_format')->first();
        $estimateSetting = EstimateSetting::first();

        \View::addLocation(base_path().'/Modules/Estimate/Resources/views');
        // return view('estimate-1', compact('estimate', 'currency', 'setting', 'estimateSetting'));
        $pdf = \PDF::loadView('estimate-1', compact('estimate', 'currency', 'setting', 'estimateSetting'));
        return $pdf->download($estimate->estimate_number.'.pdf');
    }

    /**
     * Check estimate permission.
     *
     * @param Int $id [Row id]
     * @param Int $clientId [User/Client id]
     *
     * @return Response
     */
    public function checkPermission($id, $clientId)
    {
        $estimate = Estimate::where('id', $id)
            ->where('client_id', $clientId)
            ->exists();

        if($estimate){
            return true;
        }else{

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
    private function _getEstimateNumber()
    {
        $estimate_prefix = EstimateSetting::pluck('estimate_prefix')->first();
        return $estimate_prefix.str_pad(nextAutoID(config('core.acl.estimates_table')), 6, "0", STR_PAD_LEFT);
    }
}
