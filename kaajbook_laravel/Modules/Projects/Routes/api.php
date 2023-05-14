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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('all-projects', 'ProjectsController@getAllProjects');
    Route::post('projects/project-report', 'ProjectsController@getProjectForReport');
    Route::post('projects/import', 'ProjectsController@importProject');
    Route::post('projects/{id}/change-status', 'ProjectsController@changeProjectStatus');
    Route::post('projects/delete/{id}', 'ProjectsController@destroy');
    Route::post('projects/projectsprinttasks', 'ProjectsController@getProjectSprintTasks');
    Route::put('projects/notes/{id}', 'ProjectsController@projectNotesUpdate');
    Route::get('projects/projectmembers', 'ProjectsController@getProjectMembers');
    Route::get('projects/{id}/permission', 'ProjectsController@getProjectPermission');
    Route::get('projects/my', 'ProjectsController@getMyProjects');
    Route::get('projects/getbyid/{id}', 'ProjectsController@getProjectById');
    Route::resource('projects', 'ProjectsController', ['except' => ['destroy']]);
});
