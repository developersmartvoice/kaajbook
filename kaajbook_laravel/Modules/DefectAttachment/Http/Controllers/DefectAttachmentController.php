<?php

namespace Modules\DefectAttachment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\DefectAttachment\Repositories\DefectAttachmentRepository;
use Modules\DefectAttachment\Http\Requests\DefectAttachmentRequest;

/**
 * Class DefectAttachmentController
 *
 * Defect attachment create and delete.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\DefectAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DefectAttachmentController extends Controller
{
    protected $defectAttachmentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param DefectAttachmentRepository $defectAttachment [Object]
     *
     * @return void
     */
    public function __construct(DefectAttachmentRepository $defectAttachment)
    {
        $this->defectAttachmentRepo = $defectAttachment;
    }

    /**
     * Get defect attachment by defect.
     *
     * @param Int $defect_id [Defect id]
     *
     * @return Response
     */
    public function show($defect_id)
    {
        $defectAttachment = $this->defectAttachmentRepo->findByDefectId($defect_id);
        if ($defectAttachment) {
            return response()->json($defectAttachment);
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DefectAttachmentRequest $request [Request for create defect attachment]
     *
     * @return Response
     */
    public function store(DefectAttachmentRequest $request)
    {
        $defectAttachment = $this->defectAttachmentRepo->create($request);

        if ($defectAttachment) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy defect attachment]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check task user permission
        if (!$this->defectAttachmentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->defectAttachmentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
