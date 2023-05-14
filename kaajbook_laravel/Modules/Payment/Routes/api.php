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
        Route::resource('payment-methods', 'PaymentMethodController');
        Route::post('all-payment-methods', 'PaymentMethodController@getAllPaymentMethods');
        Route::resource('payment-gateway-setting', 'PaymentGatewaySettingController');
        Route::resource('payments', 'PaymentController');
        Route::post('payments/stripe/charges', 'PaymentController@stripePaymentCharge');
        Route::post('all-payments', 'PaymentController@getAllPayments');
    }
);
