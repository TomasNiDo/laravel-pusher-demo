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

Route::get('/', function () {
    return view('welcome');
});

Route::get('notifications', 'NotificationController@index');
Route::post('notifications/notify', 'NotificationController@notify');

Route::get('auth/github', 'Auth\LoginController@redirectToProvider');
Route::get('auth/github/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('activities', 'ActivityController@index');
Route::post('activities/status-update', 'ActivityController@statusUpdate');
Route::post('activities/like/{id}', 'ActivityController@like');

Route::get('chat', 'ChatController@index');
Route::post('chat/message', 'ChatController@message');
Route::post('chat/auth', 'ChatController@auth');