<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('estimates/generate-pdf', 'EstimateController@generatePDF');
Route::get('estimates/download/{id}/{clientId}', ['uses' => 'EstimateController@download'])->name('estimates.download');