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
Route::get('settings/get-settings', 'SettingController@getSettings');
// Route::get('settings/changeApiUrl', 'SettingController@changeApiUrl');
Route::group(
    ['middleware' => 'auth:api'],
    function () {
    	Route::get('settings/sent-test-email', 'SettingController@sendTestEmail');
        Route::resource('settings','SettingController',['except' => ['edit', 'update', 'destroy']]);
        Route::resource('dashboard-settings','DashboardSettingsController',['except' => ['edit', 'update', 'destroy']]);
    }
);
