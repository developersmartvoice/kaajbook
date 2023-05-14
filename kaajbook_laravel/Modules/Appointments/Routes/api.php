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
    ['middleware' => 'auth:api'],
    function () {
    	Route::get('appointments/availabilities/{id}/{date}', 'AppointmentsController@getAllBookedTimeSlot');
    	Route::post('all-appointments', 'AppointmentsController@getAllAppointments');
    	Route::post('appointments/{id}/change-status','AppointmentsController@changeStatus');
        Route::resource('appointments','AppointmentsController');

        Route::post('appointment-recurrence/{id}/change-status', 'AppointmentRecurrenceController@changeStatus');
    }
);