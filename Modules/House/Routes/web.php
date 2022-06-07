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

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'house'], function () {
    // house Routes
    Route::get('/manage-house', 'HouseController@index');
    Route::post('/create-house', 'HouseController@store');
    Route::get('/edit-house/{id}', ['access' => ['house/edit'], 'uses' => 'HouseController@edit']);
    Route::post('/update-house/{id}', ['access' => ['house/edit'], 'uses' => 'HouseController@update']);
    Route::get('/delete-house/{id}', ['access' => ['house/delete'], 'uses' => 'HouseController@destroy']);
    Route::get('/assign/students/page', 'HouseController@assignStudentsPage');
    Route::get('/assign/students/search/house', 'HouseController@assignStudentsSearchHouse');
    Route::get('/assign/students/modal', 'HouseController@assignStudentsModal');
    Route::get('/house-appoints/{id?}', ['access' => ['house/appoints'], 'uses' => 'HouseController@houseAppoints']);
    Route::get('/edit/house-appoint/{id}', ['access' => ['house/appoints.edit'], 'uses' => 'HouseController@editHouseAppoint']);
    Route::post('/store/house-appoint', ['access' => ['house/appoints.create', 'house/appoints.edit'], 'uses' => 'HouseController@storeHouseAppoints']);
    Route::get('/delete/house-appoint/{id}', ['access' => ['house/appoints.delete'], 'uses' => 'HouseController@deleteHouseAppoint']);
    Route::post('/assign/user/to/appoint', 'HouseController@assignUserToAppoint');
    Route::get('/remove/user/from/appoint/{id}', ['access' => ['house/appoints.user.delete'], 'uses' => 'HouseController@removeUserFromAppoint']);

    // Room Routes
    Route::post('/create-room', 'HouseController@createRoom');
    Route::get('/edit-room/{id}', ['access' => ['house/room.edit'], 'uses' => 'HouseController@editRoom']);
    Route::get('/delete-room/{id}', ['access' => ['house/room.delete'], 'uses' => 'HouseController@deleteRoom']);
    Route::post('/update-room/{id}', ['access' => ['house/room.edit'], 'uses' => 'HouseController@updateRoom']);
    Route::get('/assign-beds/{id}', ['access' => ['house/room.assign-beds'], 'uses' => 'HouseController@assignBeds']);
    // Room Ajax Routes
    Route::get('/find-sections/from-academic-level', 'HouseController@findSectionsFromAcaemicLevel');
    Route::get('/find-sections/from-batch', 'HouseController@findSectionsFromBatch');
    Route::get('/find-students/from-section', 'HouseController@findStudentsFromSection');
    Route::get('/assign-student/to-bed', 'HouseController@assignStudentToBed');
    Route::get('/remove-student/from-bed', 'HouseController@removeStudentFromBed');
    Route::get('/bulk/assign-students/to-bed', 'HouseController@bulkAssignStudentsToBed');
    Route::get('/bulk/remove-students/from-bed', 'HouseController@bulkRemoveStudentsFromBed');


    // Evaluation Routes
    Route::get('/cadets-evaluation', 'HouseController@cadetsEvaluation');
    Route::get('/weightage-config', ['access' => ['house/weightage-config'], 'uses' => 'HouseController@weightageConfig']);
    Route::post('/save-weightage', ['access' => ['house/weightage-config'], 'uses' => 'HouseController@saveWeightage']);
    // Evaluation Ajax Routes
    Route::get('/get-semester/from-year', 'HouseController@getSemesterFromYear');
    Route::get('/get-weightage-events/from-type', 'HouseController@getWeightageEventsFromType');
    Route::get('/delete-weightage', 'HouseController@deleteWeightage');
    Route::get('/get-events/from-type', 'HouseController@getEventsFromType');
    Route::get('/search-evaluation-table', 'HouseController@searchEvaluationTable');


    // View Houses Routes
    Route::get('/view', 'HouseController@viewHouses');
    Route::get('/search/house', 'HouseController@searchHouse');
    Route::get('/print/{id}','HouseController@print');


    // Communication Records Routes
    Route::get('/communication-records/{id?}', ['access' => ['house/communication-records'], 'uses' => 'CommunicationRecordController@index']);
    Route::post('/communication-records/with-house', ['access' => ['house/communication-records.create'], 'uses' => 'CommunicationRecordController@create']);
    Route::get('/create/communication-record/{id}', 'CommunicationRecordController@show');
    Route::post('/store/communication-record', ['access' => ['house/communication-records.create'], 'uses' => 'CommunicationRecordController@store']);

    // Communication Records Ajax Routes
    Route::get('/search/communication-records', 'CommunicationRecordController@searchCommunicationRecords');
    Route::get('/print/communication-records', 'CommunicationRecordController@printCommunicationRecords');




    // Record Score Routes
    Route::get('/record-score/{id?}', ['access' => ['house/record-score'], 'uses' => 'RecordScoreController@index']);
    Route::post('/record-score/with-house', ['access' => ['house/record-score'], 'uses' => 'RecordScoreController@create']);
    Route::get('/create/record-score/{id}', ['access' => ['house/record-score.create'], 'uses' => 'RecordScoreController@show']);
    Route::post('/store/record-score', ['access' => ['house/record-score.create'], 'uses' => 'RecordScoreController@store']);
    Route::post('/update/record-score', 'RecordScoreController@update');
    Route::get('/record-score/history/{id}', ['access' => ['house/record-score.history'], 'uses' => 'RecordScoreController@recordScoreHistory']);

    // Record Score Ajax Routes
    Route::get('/get/term/from/year', 'RecordScoreController@getTermFromYear');
    Route::get('/get/sections/from/batch', 'RecordScoreController@getSectionsFromBatch');
    Route::get('/search/record-scores', 'RecordScoreController@searchRecordScores');

    //Pocket money routes
    Route::get('/pocket-money', 'PocketMoneyController@index');
    Route::get('/pocket-money/search-cadets', 'PocketMoneyController@searchCadets');
    Route::get('/pocket-money/edit-info', 'PocketMoneyController@editInfo');
    Route::get('/pocket-money/histories/{id}', ['access' => ['house/pocket-money.histories'], 'uses' => 'PocketMoneyController@pocketMoneyHistories']);
    Route::get('/pocket-money/add-balance', 'PocketMoneyController@addBalance');
    Route::get('/pocket-money/allot-money', 'PocketMoneyController@allotMoney');
    Route::get('/pocket-money/expense', 'PocketMoneyController@expense');
});
