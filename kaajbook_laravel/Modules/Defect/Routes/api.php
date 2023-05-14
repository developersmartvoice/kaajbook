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
        Route::post('all-defects', 'DefectController@getAllDefects');
        Route::post('defect/kanban', 'DefectController@getDefectForKanban');
        Route::post('defect/defect-report', 'DefectController@getDefectForReport');
        Route::post('defect/calendar', 'DefectController@getDefectForCalendar');
        Route::post('defect/{id}/change-status','DefectController@changeDefectStatus');
        Route::post('defect/{id}/change-severity','DefectController@changeDefectSeverity');
        Route::post('defect/update-kanban', 'DefectController@updateKanban');
        Route::put('defect/notes/{id}', 'DefectController@defectNotesUpdate');
        Route::get('defect/{id}/permission/{type}', 'DefectController@getDefectPermission');
        Route::resource('defect', 'DefectController');
    }
);
