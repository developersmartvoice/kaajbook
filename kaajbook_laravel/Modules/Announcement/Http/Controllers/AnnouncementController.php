<?php

namespace Modules\Announcement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Announcement\Repositories\AnnouncementRepository;
use Modules\Announcement\Http\Requests\CreateAnnouncementRequest;
use Modules\Announcement\Http\Requests\UpdateAnnouncementRequest;
use Modules\Helper\Helpers\AdminHelper;

/**
 * Class AnnouncementController
 *
 * Announcement create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Announcement
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class AnnouncementController extends Controller
{
    protected $announcementRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param AnnouncementRepository $announcement [Object]
     *
     * @return void
     */
    public function __construct(AnnouncementRepository $announcement)
    {
        $this->announcementRepo = $announcement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->announcementRepo->findAll();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get announcement]
     *
     * @return Response
     */
    public function getAllAnnouncements(Request $request)
    {
        return $this->announcementRepo->getAllAnnouncements($request);
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
        return $this->announcementRepo->findById($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAnnouncementRequest $request [Request for create announcement]
     *
     * @return Response
     */
    public function store(CreateAnnouncementRequest $request)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(13, 'created')) {
            return response()->json("Access denied", 403);
        }

        if ($this->announcementRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update announcement]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function update(UpdateAnnouncementRequest $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(13, 'edited')) {
            return response()->json("Access denied", 403);
        }

        if ($this->announcementRepo->update($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy announcement]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // --
        // Check role/permission
        if (!AdminHelper::can_action(13, 'deleted')) {
            return response()->json("Access denied", 403);
        }

        if ($this->announcementRepo->delete($request, $id)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
}
