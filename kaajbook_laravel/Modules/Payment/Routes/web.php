<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\SslCommerzPaymentController;

Route::group(['middleware' => 'web', 'namespace' => 'Modules\Payment\Http\Controllers'], function () {
    // Existing payment route
    Route::get('payments/download/{id}/{clientId}', 'PaymentController@download')->name('payment.download');

    // SSLCOMMERZ Start
    Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
    Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

    Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
    Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

    Route::post('/success', [SslCommerzPaymentController::class, 'success']);
    Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
    Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

    Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
    // SSLCOMMERZ End
});
