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
        Route::post('all-incident', 'IncidentController@getAllIncidents');
        Route::post('incident/kanban', 'IncidentController@getIncidentForKanban');
        Route::post('incident/calendar','IncidentController@getIncidentForCalendar');
        Route::post('incident/incident-report','IncidentController@getIncidentForReport');
        Route::post('incident/{id}/change-status','IncidentController@changeStatus');
        Route::post('incident/{id}/change-severity','IncidentController@changeIncidentSeverity');
        Route::post('incident/update-kanban', 'IncidentController@updateKanban');
        Route::put('incident/notes/{id}', 'IncidentController@incidentNotesUpdate');
        Route::get('incident/{id}/permission/{type}', 'IncidentController@getIncidentPermission');
        Route::resource('incident', 'IncidentController');
    }
);
