<?php

namespace Modules\Estimate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Estimate\Http\Requests\CreateEstimateRequest;
use Modules\Estimate\Http\Requests\UpdateEstimateRequest;
use Modules\Estimate\Repositories\EstimateRepository;
use Modules\Helper\Helpers\AdminHelper;
use PDF;

/**
 * Class EstimateController
 *
 * Estimate create, update, delete and view.
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
class EstimateController extends Controller
{
    protected $estimateRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param EstimateRepository $estimateRepo [Object]
     *
     * @return void
     */
    public function __construct(EstimateRepository $estimateRepo)
    {
        $this->estimateRepo = $estimateRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get estimates]
     *
     * @return Response
     */
    public function getAllEstimates(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(112, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->estimateRepo->getAllEstimates($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create estimate]
     *
     * @return Response
     */
    public function store(CreateEstimateRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(112, 'created')) {
            return response()->json("Access denied", 403);
        }

        $estimate = $this->estimateRepo->create($request);

        if ($estimate) {
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
        if (!AdminHelper::can_action(112, 'view')) {
            return response()->json("Access denied", 403);
        }

        $estimate = $this->estimateRepo->findById($id);
        if ($estimate) {
            return response()->json($estimate);
        }
        return response()->json('error', 401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update estimate]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateEstimateRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(112, 'edited')) {
            return response()->json("Access denied", 403);
        }

        $estimate = $this->estimateRepo->update($request, $id);

        if ($estimate) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy estimate]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(112, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->estimateRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Change estimate status.
     *
     * @param Request $request [Request for change estimate status]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeEstimateStatus(Request $request, $id)
    {
        if ($this->estimateRepo->changeEstimateStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Download estimate PDF.
     *
     * @param Int $id [Row id]
     * @param Int $clientId [User/Client id]
     *
     * @return Response
     */
    public function download($id, $clientId)
    {
        $id = decrypt($id);
        // --
        // Check role/permission
        if (!$this->estimateRepo->checkPermission($id, $clientId)) {
            return redirect('/');
        }
        
        return $this->estimateRepo->download($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $data = ['title' => 'Welcome to VipsPM'];
        \View::addLocation(base_path().'/Modules/Estimate/Resources/views');
        $pdf = PDF::loadView('mypdf', $data);
        return $pdf->stream('chetsapp.pdf');
        // return \View::make('mypdf', compact('data'));
        // return $pdf->save(public_path().'/itsolutionstuff.pdf');
        // return $pdf->download('itsolutionstuff.pdf');
    }
}
