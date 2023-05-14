<?php

namespace Modules\Meeting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Meeting\Repositories\MeetingRepository;
use Modules\Meeting\Http\Requests\CreateMeetingRequest;
use Modules\Meeting\Http\Requests\UpdateMeetingRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class MeetingController
 *
 * Meeting create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Meeting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class MeetingController extends Controller
{
    protected $meetingRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param MeetingRepository $meeting [Object]
     *
     * @return void
     */
    public function __construct(MeetingRepository $meeting)
    {
        $this->meetingRepo = $meeting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(10, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->meetingRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get meeting]
     *
     * @return Response
     */
    public function getAllMeetings(Request $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(10, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->meetingRepo->getAllMeetings($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create meeting]
     *
     * @return Response
     */
    public function store(CreateMeetingRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(10, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->meetingRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json(
                ['error' => 'Meeting has not been created.'], 
                400
            );
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
        if (!AdminHelper::can_action(10, 'view')) {
            return response()->json("Access denied", 403);
        }

        return $this->meetingRepo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMeetingRequest $request [Request for update meeting]
     * @param Int                  $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateMeetingRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(10, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->meetingRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Meeting has not been found.'], 400);
        }
    }

    /**
     * Change meeting status.
     *
     * @param Request $request [Request for change meeting status]
     * @param Int     $id      [Row id]
     *
     * @return Response
     */
    public function changeStatus(Request $request, $id)
    {
        // --
        // Check project user permission
        if (!AdminHelper::can_action(10, 'edited')) {
            return response()->json("Access denied", 403);
        }
        
        if ($this->meetingRepo->changeStatus($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Requests $request [Request for destroy meeting]
     * @param Int      $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(10, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->meetingRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json(['error' => 'Meeting has not been found.'], 400);
        }
    }

    /**
     * Get meetings for calendar.
     *
     * @return Response
     */
    public function getMeetingForCalendar()
    {   
        return $this->meetingRepo->getMeetingForCalendar();
    }
}
