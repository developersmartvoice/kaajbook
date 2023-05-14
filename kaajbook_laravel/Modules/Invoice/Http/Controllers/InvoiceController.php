<?php

namespace Modules\Invoice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Invoice\Http\Requests\CreateInvoiceRequest;
use Modules\Invoice\Http\Requests\UpdateInvoiceRequest;
use Modules\Invoice\Repositories\InvoiceRepository;

/**
 * Class InvoiceController
 *
 * Invoice create, update, delete and view.
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
class InvoiceController extends Controller
{
    protected $invoiceRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param InvoiceRepository $invoiceRepo [Object]
     *
     * @return void
     */
    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get invoices]
     *
     * @return Response
     */
    public function getAllInvoices(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(113, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->invoiceRepo->getAllInvoices($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create invoice]
     *
     * @return Response
     */
    public function store(CreateInvoiceRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(113, 'created')) {
            return response()->json("Access denied", 403);
        }

        $invoice = $this->invoiceRepo->create($request);

        if ($invoice) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param Int $id [Row id]
     *
     * @return Response
     */
    public function show($id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(113, 'view')) {
            return response()->json("Access denied", 403);
        }

        $invoice = $this->invoiceRepo->findById($id);
        if ($invoice) {
            return response()->json($invoice);
        }
        return response()->json('error', 401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update invoice]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateInvoiceRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(113, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $invoice = $this->invoiceRepo->update($request, $id);

        if ($invoice) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy invoice]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(113, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->invoiceRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Change invoice status.
     *
     * @param Request $request [Request for change invoice status]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeInvoiceStatus(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(113, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->invoiceRepo->changeInvoiceStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Download invoice PDF.
     *
     * @param Int $id       [Row id]
     * @param Int $clientId [User/Client id]
     *
     * @return Response
     */
    public function download($id, $clientId)
    {
        $id = decrypt($id);
        // --
        // Check role/permission
        if (!$this->invoiceRepo->checkPermission($id, $clientId)) {
            return redirect('/');
        }
        
        return $this->invoiceRepo->download($id);
    }
}
