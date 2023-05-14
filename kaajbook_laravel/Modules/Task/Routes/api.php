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
    ],
    function () {
        Route::post('taskboard', 'TaskController@getTaskForTaskBoard');
        Route::get('tasks/calendar', 'TaskController@getTasksForCalendar');
        Route::post('tasks/status-list', 'TaskController@updateKanban');
        // Route::get('tasks/get-generated-id', 'TaskController@getGeneratedId');
        Route::post('all-tasks', 'TaskController@getAllTask');
        Route::post('tasks/{taskId}/change-status', 'TaskController@changeStatus');
        Route::post('tasks/{id}/change-priority','TaskController@changeTaskPriority');
        Route::get(
            'tasks/{parent_task_id}/parent-task',
            'TaskController@getParentTask'
        );
        Route::post('tasks/import', 'TaskController@importTask');
        Route::put('tasks/notes/{id}', 'TaskController@taskNotesUpdate');
        // Route::get(
        //     'tasks/{parent_task_id}/subtask-count-by-parent',
        //     'TaskController@getSubTaskCountByParent'
        // );
        Route::post('tasks/task-report', 'TaskController@getTaskForReport');
        Route::post('tasks/convertsprinttask-to-task', 'TaskController@convertSprintTaskToTask');
        Route::get('tasks/{id}/permission/{type}', 'TaskController@getTaskPermission');
        Route::resource('tasks', 'TaskController');
    }
);
