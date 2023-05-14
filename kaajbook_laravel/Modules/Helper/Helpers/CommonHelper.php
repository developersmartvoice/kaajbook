<?php

namespace Modules\Helper\Helpers;

use Carbon\Carbon;

/**
 * Class CommonHelper
 *
 * The Helper is Defined for Common.
 *
 * PHP version 7.1.3
 *
 * @category  Helper
 * @package   Modules\Helper
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CommonHelper
{
    /**
     * Get decimal time difference.
     *
     * @param String $start_time [Start time]
     * @param String $end_time   [End time]
     *
     * @return decimal
     */
    public function getDecimalTimeDiff($start_time, $end_time)
    {
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        return round(abs($end_time - $start_time) / 3600, 2);
    }

    /**
     * Get hour time difference.
     *
     * @param String $start_time [Start time]
     * @param String $end_time   [End time]
     *
     * @return time
     */
    public function getHourTimeDiff($start_time, $end_time)
    {
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        return str_pad($start->diffInHours($end), 2, 0, STR_PAD_LEFT). ':' .$start->diff($end)->format('%I');

        // $start_time = date_create($start_time);
        // $end_time = date_create($end_time);
        // $interval = date_diff($end_time, $start_time);
        // return $interval->format('%H:%I');
    }

    /**
     * Get today.
     *
     * @return Array
     */
    public function getToday()
    {
        return [
            'period_from' => date('Y-m-d'),
            'period_to' => date('Y-m-d 23:59:59')
        ];
    }

    /**
     * Get this month.
     *
     * @return Array
     */
    public function getThisMonth()
    {
        return [
            'period_from' => date('Y-m-01'),
            'period_to' => date('Y-m-t 23:59:59')
        ];
    }

    /**
     * Get last month.
     *
     * @return Array
     */
    public function getLastMonth()
    {
        return [
            'period_from' => date('Y-m-01', strtotime('-1 MONTH')),
            'period_to' => date('Y-m-t 23:59:59', strtotime('-1 MONTH'))
        ];
    }

    /**
     * Get this week.
     *
     * @return Array
     */
    public function getThisWeek()
    {
        return [
            'period_from' => date('Y-m-d', strtotime('monday this week')),
            'period_to' => date('Y-m-d 23:59:59', strtotime('sunday this week'))
        ];
    }

    /**
     * Get last week.
     *
     * @return Array
     */
    public function getLastWeek()
    {
        return [
            'period_from' => date('Y-m-d', strtotime('monday last week')),
            'period_to' => date('Y-m-d 23:59:59', strtotime('sunday last week'))
        ];
    }

    /**
     * Get current year.
     *
     * @return Array
     */
    public function getCurrentYear()
    {
        return [
            'period_from' => date('Y-m-d', strtotime('first day of January')),
            'period_to' => date('Y-m-d 23:59:59', strtotime('last day of December'))
        ];
    }

    /**
     * Convert period.
     *
     * @param date
     * @param date
     *
     * @return Array
     */
    public function convertPeriod($period_from, $period_to)
    {
        return [
            'period_from' => date("Y-m-d", strtotime($period_from)),
            'period_to' => date("Y-m-d 23:59:59", strtotime($period_to))
        ];
    }

    /**
     * Convert decimal time into time in the format hh:mm
     *
     * @param integer The time as a decimal value.
     *
     * @return string $time The converted time value.
     */
    public function convertDecimalToTime($decimal)
    {
        // Start by converting to seconds
        $seconds = ($decimal * 3600);
        // We're given hours, so let's get those the easy way
        $hours = floor($decimal);
        // Since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // Calculate minutes left
        // $minutes = floor($seconds / 60);
        $minutes = round($seconds / 60);
        // return the time formatted HH:MM:SS
        return str_pad($hours, 2, 0, STR_PAD_LEFT).":".str_pad($minutes, 2, 0, STR_PAD_LEFT);
    }
}
