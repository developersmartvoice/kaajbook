<?php

namespace Modules\IncidentComment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\IncidentComment\Repositories\IncidentCommentRepository;
use Modules\IncidentComment\Http\Requests\IncidentCommentRequest;

/**
 * Class IncidentCommentController
 *
 * Incident comment create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\IncidentComment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class IncidentCommentController extends Controller
{
    protected $incidentcommentRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param IncidentCommentRepository $incidentcomment [Object]
     *
     * @return void
     */
    public function __construct(IncidentCommentRepository $incidentcomment)
    {
        $this->incidentcommentRepo = $incidentcomment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->incidentcommentRepo->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create incident comment]
     *
     * @return Response
     */
    public function store(IncidentCommentRequest $request)
    {
        $incidentcomment = $this->incidentcommentRepo->create($request);

        if ($incidentcomment) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update incident comment]
     * @param int   $id [Incident comment Id]
     *
     * @return Response
     */
    public function update(IncidentCommentRequest $request, $id)
    {
        if ($this->incidentcommentRepo->update($request, $id)) {
            return response()->json("success");
        } else {
            return response()->json("error", 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy incident comment]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check task user permission
        if (!$this->incidentcommentRepo->checkPermission($id)) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->incidentcommentRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }
}
