<?php

use Illuminate\Http\Request;

/**
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
        Route::post('all-clients', 'ClientController@getAllClients');
        Route::post('clients/delete/{id}', 'ClientController@destroy');
        Route::put(
            'clients/status-change/{id}',
            'ClientController@setActiveDeactiveUser'
        );
        Route::get('clients/withtrashed', 'ClientController@getClientsWithTrashed');
        Route::resource('clients', 'ClientController', ['except' => ['destroy']]);
    }
);
