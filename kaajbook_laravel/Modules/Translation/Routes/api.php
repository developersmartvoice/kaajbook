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
        Route::post('all-translations', 'TranslationController@getAllTranslations');
        Route::get('translations/active', 'TranslationController@getAllActiveTranslations');
        Route::post(
            'translations/{id}/change-status', 
            'TranslationController@changeStatus'
        );
        Route::resource('translations', 'TranslationController');
    }
);
