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

Route::group(['middleware' => 'auth:api'],function () {
	Route::get('projectplannersprint/sprintbyproject/{id}', 'ProjectPlannerSprintController@getSprintByProject');
    Route::put(
        'projectplannersprint/{id}/change-status',
        'ProjectPlannerSprintController@changeStatus'
    );
    Route::resource('projectplannersprint', 'ProjectPlannerSprintController');
});
