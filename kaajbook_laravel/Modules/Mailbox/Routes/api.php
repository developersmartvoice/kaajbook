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
        Route::post('mailbox/unread-emails', 'MailboxController@getUnReadMailByUser');
        Route::post('all-mailbox', 'MailboxController@index');
        Route::post('mailbox/reads', 'MailboxController@setReadMail');
        Route::post('mailbox/favourite', 'MailboxController@setFavouriteMail');
        Route::post('mailbox/remove', 'MailboxController@removeMarkMails');
        Route::post('mailbox/files/upload', 'MailboxController@uploadAttachementFiles');
        Route::post('mailbox/attachment/remove', 'MailboxController@removeAttachementFile');
        Route::post('mailbox/unfavourite', 'MailboxController@setUnFavouriteMail');
        Route::resource('mailbox', 'MailboxController');
    }
);
