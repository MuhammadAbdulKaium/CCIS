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

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'healthcare'], function () {
    Route::get('/', 'HealthCareController@index');


    // Prescription
    Route::get('/prescription', 'HealthCareController@index');
    Route::get('/cadet/search-section', 'HealthCareController@searchSection');
    Route::get('/create/prescription', ['access' => ['healthcare/prescription.create'], 'uses' => 'HealthCareController@create']);
    Route::post('/store/prescription', ['access' => ['healthcare/prescription.create'], 'uses' => 'HealthCareController@store']);
    Route::get('/edit/prescription/{id}', ['access' => ['healthcare/prescription.edit'], 'uses' => 'HealthCareController@edit']);
    Route::post('/update/prescription/{id}', ['access' => ['healthcare/prescription.edit'], 'uses' => 'HealthCareController@update']);
    Route::get('/prescription/status/change/{id}/{status}', ['access' => ['healthcare/prescription.status-change'], 'uses' => 'HealthCareController@prescriptionStatusChange']);
    Route::post('/close/prescription/{id}', ['access' => ['healthcare/prescription.close'], 'uses' => 'HealthCareController@closePrescription']);
    Route::get('/print/prescription/{id}', ['access' => ['healthcare/prescription.print'], 'uses' => 'HealthCareController@printPrescription']);
    Route::get('/delete/prescription/{id}', ['access' => ['healthcare/prescription.delete'], 'uses' => 'HealthCareController@destroy']);

    // attach-file remove
    Route::get('/prescription/attach-file/remove','HealthCareController@attachFileRemove');

    //Prescription Ajax Routes
    Route::get('/users/from/user-type', 'HealthCareController@usersFromUserType');


    // Investigation
    Route::get('/investigation', 'InvestigationController@index');
    Route::get('/create/investigation', ['access' => ['healthcare/investigation.create'], 'uses' => 'InvestigationController@create']);
    Route::get('/edit/investigation/{id}', ['access' => ['healthcare/investigation.edit'], 'uses' => 'InvestigationController@edit']);
    Route::post('/store/investigation', ['access' => ['healthcare/investigation.create'], 'uses' => 'InvestigationController@store']);
    Route::post('/update/investigation/{id}', ['access' => ['healthcare/investigation.edit'], 'uses' => 'InvestigationController@update']);
    Route::get('/delete/investigation/{id}',  ['access' => ['healthcare/investigation.delete'], 'uses' => 'InvestigationController@destroy']);

    // Investigation Report
    Route::get('/investigation/reports', 'InvestigationController@investigationReports');
    Route::get('/set/report/{id}', ['access' => ['healthcare/investigation.set-report'], 'uses' => 'InvestigationController@setReport']);
    Route::post('/save/report/{id}', ['access' => ['healthcare/investigation.set-report'], 'uses' => 'InvestigationController@saveReport']);
    Route::get('/view/report/{id}', ['access' => ['healthcare/investigation.view-report'], 'uses' => 'InvestigationController@viewReport']);
    Route::get('/deliver/report/{id}', ['access' => ['healthcare/investigation.deliver-report'], 'uses' => 'InvestigationController@deliverReport']);

    // Drug Report
    Route::get('/drug/reports', 'HealthCareController@drugReports');
    Route::get('/drug/deliver/modal/{id}', ['access' => ['healthcare/drug.deliver'], 'uses' => 'HealthCareController@drugDeliverModal']);
    Route::post('/drug/deliver/{id}', ['access' => ['healthcare/drug.deliver'], 'uses' => 'HealthCareController@drugDeliver']);
    Route::get('/drug/get/stock/from/store', 'HealthCareController@getStockFromStore');
});
