<?php

namespace Modules\Timesheet\Repositories;

use Auth;
use Modules\Timesheet\Models\TimerLog;

/**
 * Class TimerLogRepository
 *
 * Timer log create, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Timesheet
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.1@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.1
 */
class TimerLogRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return Object
     */
    public function getUserTimerLog()
    {
        $user = Auth::user();
        return TimerLog::where('user_id', $user->id)->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create timer log]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $user = Auth::user();
        $input = $request->all();
        $input['user_id'] = $user->id;
        $timerLog = new TimerLog;
        $timerLog = $timerLog->create($input);
        if ($timerLog) {
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete timer log]
     * @param Int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $timerLog = TimerLog::where('user_id', $id)->first();
        if ($timerLog->forceDelete()) {
            return true;
        } else {
            return false;
        }
    }
}
