<?php

namespace Modules\Timesheet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Timesheet\Http\Requests\TimerLogRequest;
use Modules\Timesheet\Repositories\TimerLogRepository;

/**
 * Class TimerLogsController
 *
 * Timer Log create, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Timesheet
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.1@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.1
 */
class TimerLogsController extends Controller
{
    protected $timerLogRepo;

    /**
     * Instantiate a new repository instance.
     *
     * @param TimerLogRepository $timerLogRepo [Object]
     *
     * @return void
     */
    public function __construct(TimerLogRepository $timerLogRepo)
    {
        $this->timerLogRepo = $timerLogRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->timerLogRepo->getUserTimerLog();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TimerLogRequest $request [Request for create timer log]
     *
     * @return Response
     */
    public function store(TimerLogRequest $request)
    {
        if ($this->timerLogRepo->create($request)) {
            return response()->json('success');
        } else {
            return response()->json('error', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for destroy timer log]
     * @param int     $id      [Row id]
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        if ($this->timerLogRepo->delete($request, $id)) {
            return response()->json('success', 200);
        } else {
            return response()->json('error', 400);
        }
    }
}
