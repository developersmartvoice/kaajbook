<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    ['middleware' => 'auth:api'], function () {
        Route::post('all-leaves', 'LeaveController@getAllLeave');
        Route::post('leaves/change-status/{id}', 'LeaveController@changeLeaveStatus');
        Route::post('leaves/report', 'LeaveController@getLeavesForReport');
        Route::post('leaves/report-details', 'LeaveController@leaveReportDetails');
        Route::get('leaves/calendar', 'LeaveController@getLeavesForCalendar');
        Route::get('leaves/pending', 'LeaveController@getLeavesForBox');
        Route::resource('leaves', 'LeaveController');
    }
);
