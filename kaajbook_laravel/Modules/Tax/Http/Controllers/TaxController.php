<?php

namespace Modules\Tax\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Tax\Http\Requests\CreateTaxRequest;
use Modules\Tax\Http\Requests\UpdateTaxRequest;
use Modules\Tax\Repositories\TaxRepository;

/**
 * Class TaxController
 *
 * Tax CRUD functionality.
 *
 * Tax create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Tax
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class TaxController extends Controller
{
    protected $taxRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param TaxRepository $tax [Object]
     *
     * @return void
     */
    public function __construct(TaxRepository $tax)
    {
        $this->taxRepo = $tax;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->taxRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTaxRequest $request [Params for create tax]
     *
     * @return Response
     */
    public function store(CreateTaxRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(116, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->taxRepo->create($request)) {
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
        if (!AdminHelper::can_action(116, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->taxRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaxRequest $request [Params for update tax]
     * @param int              $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateTaxRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(116, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $isUsed = $this->_isUsed($id);
        if($isUsed){
            return $isUsed;
        }

        if ($this->taxRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Params for destroy tax]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(116, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        $isUsed = $this->_isUsed($id);
        if($isUsed){
            return $isUsed;
        }

        if ($this->taxRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * Check tax is used by tax, estimate or invoice.
     *
     * @return Response
     */
    private function _isUsed($id)
    {
        $itemTax = DB::table(config('core.acl.item_tax_table'))->where('tax_id', $id)->get()->toArray();
        $estimateItemTax = DB::table(config('core.acl.estimate_item_taxes_table'))->where('tax_id', $id)->get()->toArray();
        $invoiceItemTax = DB::table(config('core.acl.invoice_item_taxes_table'))->where('tax_id', $id)->get()->toArray();
        $data = array_merge_recursive($itemTax, $estimateItemTax, $invoiceItemTax);
        if(!empty($data)){
            return response()->json(['errors' => ['msg' => ["The Id of the tax is using."]]], 422);
        }
        return false;
    }
}
