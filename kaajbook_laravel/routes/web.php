<?php

use Symfony\Component\Console\Output\BufferedOutput;

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

Route::get('/', function () {
    if (file_exists(storage_path('installed'))) {
        if (!is_null(config('app.front_url'))) {
            return redirect(config('app.front_url'));
        }
        return response()->json('Success');
    } else {
        Artisan::call('config:clear', []);
        \View::addLocation(base_path() . '/Modules/Installer/Views');
        \View::addNamespace('theme', base_path() . '/Modules/Installer/Views');
        return View::make('theme::welcome');
    }
})->name('index');

// Application cache cleared.
Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    return "Application cache cleared!";
});

// Configuration cached.
Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return "Configuration cache cleared!<br>Configuration cached successfully!";
});

// phpinfo.
// Route::get('/phpinfo', function() {
//    return response()->json([
//     'stuff' => phpinfo()
//    ]);
// });

// Schedule run via URL.
// Route::get('schedule/run', function () {
//     Artisan::call('schedule:run', [], $outputLog);
// });

// Route::get('/artisan/{cmd}/', function ($cmd) {
//     $outputLog = new BufferedOutput;
//     pr("php artisan " . $cmd);
//     Artisan::call($cmd, [], $outputLog);
//     pr($outputLog);
//     return "Done!";
// });

// Queue work.
// Route::get('/queue/work', function () {
//     $r = Artisan::call('queue:work');
//     pr($r);
//     return "Done!";
// });
