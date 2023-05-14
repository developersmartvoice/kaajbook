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
        Route::get('updater/check', 'UpdateController@checkUpdateVersion');
        Route::get('updater/current_version', 'UpdateController@getCurrentVersion');
        Route::get('updater/update', 'UpdateController@updateSystem');
    }
);
Route::get('updater/updatedb', 'UpdateController@updateDB');
