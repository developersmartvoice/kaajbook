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
    ], function () {
        Route::get(
            'file-browser/breadcrumb', 
            'FileBrowserController@getFolderBreadcrumb'
        );
        Route::resource('file-browser', 'FileBrowserController');
        Route::post('files/upload', 'FileController@uploadFile');
        Route::post(
            'files/attachment/remove', 
            'FileController@removeAttachementFile'
        );
        Route::resource('files', 'FileController');
    }
);
