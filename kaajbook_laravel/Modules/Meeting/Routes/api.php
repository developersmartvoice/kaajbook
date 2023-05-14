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
    [
    'middleware' => 'auth:api'
    ], function () {
    	Route::get('meetings/calendar', 'MeetingController@getMeetingForCalendar');
        Route::post('all-meetings', 'MeetingController@getAllMeetings');
        Route::post('meetings/{id}/change-status', 'MeetingController@changeStatus');
        Route::resource('meetings', 'MeetingController');
    }
);
