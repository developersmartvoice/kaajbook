<?php

namespace Modules\DefectComment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\DefectComment\Repositories\DefectCommentRepository;
use Modules\DefectComment\Http\Requests\DefectCommentRequest;

/**
 * Class DefectCommentController
 *
 * DefectComment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\DefectComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class DefectCommentController extends Controller
{
    protected $defectCommentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param DefectCommentRepository $defectComment [Object]
     *
     * @return void
     */
    public function __construct(DefectCommentRepository $defectComment)
    {
        $this->defectCommentRepo = $defectComment;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DefectCommentRequest $request [Request for create defect comment]
     *
     * @return Response
     */
    public function store(DefectCommentRequest $request)
    {
        $defectComment = $this->defectCommentRepo->create($request);

        if ($defectComment) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DefectCommentRequest $request [Request for update defect comment]
     * @param int               $id      [Row id]
     *
     * @return Response
     */
    public function update(DefectCommentRequest $request, $id)
    {
        if ($this->defectCommentRepo->update($request, $id)) {
            return response()->json("success");
        } else {
            return response()->json("error", 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy defect comment]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check task user permission
        if (!$this->defectCommentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->defectCommentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
