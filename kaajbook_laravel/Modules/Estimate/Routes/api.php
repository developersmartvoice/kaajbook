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
        Route::resource('estimate-setting', 'EstimateSettingController');
        Route::resource('estimates', 'EstimateController');
        Route::post('all-estimates', 'EstimateController@getAllEstimates');
        Route::post('estimates/change-status/{id}', 'EstimateController@changeEstimateStatus');
    }
);
