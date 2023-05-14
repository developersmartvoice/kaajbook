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
        Route::resource('slack-setting', 'SlackSettingController');
    }
);

Route::get('slack/auth/redirect', 'SlackController@getSlackCode');
Route::post('slack/channel/message', 'SlackController@createChannelMessage');
Route::post('slack/command1', 'SlackController@getSlackCommand1');
