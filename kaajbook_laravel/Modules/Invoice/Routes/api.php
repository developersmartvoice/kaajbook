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
        Route::resource('invoice-setting', 'InvoiceSettingController');
        Route::resource('invoices', 'InvoiceController');
        Route::post('all-invoices', 'InvoiceController@getAllInvoices');
        Route::post('invoices/change-status/{id}', 'InvoiceController@changeInvoiceStatus');
    }
);
