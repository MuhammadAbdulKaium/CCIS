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

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'levelofapproval'], function () {
    Route::get('/', 'LevelOfApprovalController@index');
    Route::get('/show/approval-layers/{id}', 'LevelOfApprovalController@show');
    Route::get('/edit/approval-layers/{id}', 'LevelOfApprovalController@edit');
    Route::post('/update/approval-layers/{id}', 'LevelOfApprovalController@update');
    Route::get('/get/persons/from/role', 'LevelOfApprovalController@getPersonsFromRole');
    Route::get('/alert/notification', 'LevelOfApprovalController@alertNotificationPage');
    Route::get('get/alert/notification/table', 'LevelOfApprovalController@alertNotificationTable');
});
