<?php

namespace Modules\Holiday\Repositories;

use Modules\Holiday\Models\Holiday;
use Auth;


/**
 * Class HolidayRepository
 *
 * Holiday create, update, delete and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Holiday
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class HolidayRepository
{

    /**
     * Display a listing of the resource.
     *
     * @param Array $input [Input array]
     *
     * @return Object
     */
    public function getHolidays($input)
    {
        // --
        // Check for year
        if (!empty($input['year'])) {
            $year = $input['year'];
        } else {
            $year = date('Y');
        }
        $holiday['years'] = $this->_getHolidayList($year);
        return $holiday;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request [Request for get holiday]
     *
     * @return Object
     */
    public function findAll($request)
    {
        $columns = array(
            0 => 'date',
            1 => 'date',
            2 => 'event_name',
            3 => 'location'
        );
        
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $holidays = new Holiday();
        $totalData = $holidays->count();
        $totalFiltered = $totalData;

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $holidays = $holidays->where(
                function ($query) use ($search) {
                    $query->where('date', 'LIKE', "%{$search}%")
                        ->orWhere('event_name', 'LIKE', "%{$search}%")
                        ->orWhere('location', 'LIKE', "%{$search}%");
                }
            );

            $totalFiltered = $holidays->count();
        }

        $data = $holidays->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    /**
     * Display the specified resource in storage.
     *
     * @param Int $id [Row id]
     *
     * @return Object
     */
    public function findById($id)
    {
        return Holiday::findOrfail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request [Request for create holiday]
     *
     * @return Boolean
     */
    public function create($request)
    {
        $input = $request->all();
        $holiday = new Holiday;
        $holiday->fill($input);

        if ($holiday->save()) {
            //--
            // Add activity
            createUserActivity(
                Holiday::MODULE_NAME,
                $holiday->id,
                $request->method(),
                $holiday->event_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request [Request for update holiday]
     * @param int     $id      [Row id]
     *
     * @return Boolean
     */
    public function update($request, $id)
    {
        $input = $request->all();
        $holiday = Holiday::findOrFail($id);
        $holiday->fill($input);

        if ($holiday->save()) {
            //--
            // Add activity
            createUserActivity(
                Holiday::MODULE_NAME,
                $holiday->id,
                $request->method(),
                $holiday->event_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request [Request for delete holiday]
     * @param int     $id      [Row id]
     *
     * @return Boolean
     */
    public function delete($request, $id)
    {
        $holiday = Holiday::findOrFail($id);
        if ($holiday->delete()) {
            //--
            // Add activity
            createUserActivity(
                Holiday::MODULE_NAME,
                $holiday->id,
                $request->method(),
                $holiday->event_name,
                $request->ip()
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get holiday list based on year.
     *
     * @param Date $year [Holiday year]
     *
     * @return Array
     */
    private function _getHolidayList($year)
    {
        $get_holiday_list = [];
        for ($i = 1; $i <= 12; $i++) { // Query for months
            if ($i >= 1 && $i <= 9) {
                // If i<=9 concate with Mysql.
                // becuase on Mysql query fast in two digit like 01.
                $start_date = $year . "-" . '0' . $i . '-' . '01';
                $end_date = $year . "-" . '0' . $i . '-' . '31';
            } else {
                $start_date = $year . "-" . $i . '-' . '01';
                $end_date = $year . "-" . $i . '-' . '31';
            }
            $get_holiday_list[$i] = $this->_getHolidayListByDate(
                $start_date,
                $end_date
            );
        }
        return $get_holiday_list;
    }

    /**
     * Get holiday list from DB.
     *
     * @param Date $start_date [Holiday from date]
     * @param Date $end_date   [Holiday to date]
     *
     * @return Array
     */
    private function _getHolidayListByDate($start_date, $end_date)
    {
        return Holiday::where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->get();
    }


    /**
     * Get holiday list from DB.
     *
     * @param Date $request [Holiday year]
     *
     * @return Array
     */
    public function yearAllHolidays($request)
    {
        return Holiday::whereYear('date', $request['year'])->get();
    }
}
