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

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'event'], function () {

    // Event index
    Route::get('/', 'EventController@index');
    Route::get('/event/create', ['access' => ['event/add'], 'uses' => 'EventController@create']);
    Route::post('/store', ['access' => ['event/add'], 'uses' => 'EventController@store']);
    Route::get('/delete/{id}', ['access' => ['event/delete'], 'uses' => 'EventController@destroy']);
    Route::get('/edit/{id}', ['access' => ['event/edit'], 'uses' => 'EventController@edit']);
    Route::post('/update/{id}', ['access' => ['event/edit'], 'uses' => 'EventController@update']);
    Route::get('/assign/date/{id}', ['access' => ['event/assign-date'], 'uses' => 'EventController@assignDate']);
    Route::post('/search/students', 'EventController@searchStudents');
    Route::post('/save/event/date', ['access' => ['event/assign-date'], 'uses' => 'EventController@saveEventDate']);
    Route::get('/edit/team/{id}', ['access' => ['event/event-team.edit'], 'uses' => 'EventController@editEventTeam']);
    Route::post('/update/event/team', ['access' => ['event/event-team.edit'], 'uses' => 'EventController@updateEventTeam']);
    Route::get('/score/sheet', 'EventController@scoreSheet');

    //Event Ajax Routes
    Route::get('/find/sub_cat/{id}', 'EventController@getAjaxTypeCategory');
    Route::get('/find/activity/{id}', 'EventController@getAjaxCategoryActivity');
    Route::get('/get/students/from/house', 'EventController@getStudentsFromHouse');
    Route::get('/get/sections/students/from/batch', 'EventController@getSectionsStudentsFromBatch');
    Route::get('/get/students/from/section', 'EventController@getStudentsFromSection');
    Route::get('/add/team', 'EventController@addTeam');
    Route::get('/remove/event/date', 'EventController@removeEventDate');
    Route::get('/delete/event/team', 'EventController@deleteEventTeam');


    // Event Marks Routes
    Route::get('/marks', 'EventController@eventMarks');

    // Event Marks Ajax Routes
    Route::get('/date-time/from/event', 'EventController@dateTimeFromEvent');
    Route::get('/team/from/date-time', 'EventController@teamFromDateTime');
    Route::post('/student-search/for/marks', 'EventController@studentSearchForMarks');
    Route::post('/save/students/event-marks', 'EventController@saveStudentsEventMarks');


    //Route by Mazharul
    //swimming Form
    Route::get('/swimming','ScoreSheetFrontController@swimmingForm');
    Route::get('/swimmingpdf','ScoreSheetFrontController@swimmingFormPdf');
    Route::get('/swimming-final','ScoreSheetFrontController@swimmingFinal');
    Route::get('/swimming-final-pdf','ScoreSheetFrontController@swimmingFinalPdf');


    Route::get('/basketball','ScoreSheetFrontController@basketballForm');
    Route::get('/basketball-pdf','ScoreSheetFrontController@basketballFormPdf');
    Route::get('/cricket-pdf','ScoreSheetFrontController@cricketFormPdf');
    Route::get('/cricket','ScoreSheetFrontController@cricket');
    Route::get('/cricket-final-pdf','ScoreSheetFrontController@cricketFinalPdf');
    Route::get('/cricket-final','ScoreSheetFrontController@cricketFinal');

    Route::get('/football-pdf','ScoreSheetFrontController@footballFormPdf');
    Route::get('/football','ScoreSheetFrontController@footballForm');
    Route::get('/volleyball-pdf','ScoreSheetFrontController@volleyballFormPdf');
    Route::get('/volleyball','ScoreSheetFrontController@volleyballForm');
    Route::get('/football-final-pdf','ScoreSheetFrontController@footballballFinalFormPdf');
    Route::get('/football-final','ScoreSheetFrontController@footballballFinalForm');
    Route::get('/cross-country-pdf','ScoreSheetFrontController@crossCountryFormPdf');
    Route::get('/cross-country','ScoreSheetFrontController@crossCountryForm');

    Route::get('/cross-country-final-pdf','ScoreSheetFrontController@crossCountryFinalPdf');
    Route::get('/cross-country-final','ScoreSheetFrontController@crossCountryFinal');

    Route::get('/obstacle-form-pdf','ScoreSheetFrontController@obstacleFormPdf');
    Route::get('/obstacle-form','ScoreSheetFrontController@obstacleForm');
    Route::get('/obstacle-final-pdf','ScoreSheetFrontController@obstacleFinalPdf');
    Route::get('/obstacle-final','ScoreSheetFrontController@obstacleFinal');

    Route::get('/quiz-form-pdf','ScoreSheetFrontController@quizFormPdf');
    Route::get('/quiz-form','ScoreSheetFrontController@quizForm');
    Route::get('/wall-magazine-pdf','ScoreSheetFrontController@wallMagazineFormPdf');
    Route::get('/wall-magazine','ScoreSheetFrontController@wallMagazineForm');

    Route::get('/math-olympiad-form-pdf','ScoreSheetFrontController@mathOlympiadFormPdf');
    Route::get('/math-olympiad-form','ScoreSheetFrontController@mathOlympiadForm');

    Route::get('/drill-form-pdf','ScoreSheetFrontController@drillFormPdf');
    Route::get('/drill-form','ScoreSheetFrontController@drillForm');
    Route::get('/drill-final-pdf','ScoreSheetFrontController@drillFinalPdf');
    Route::get('/drill-final','ScoreSheetFrontController@drillFinal');

    Route::get('/table-tennis-pdf','ScoreSheetFrontController@tableTennisFormPdf');
    Route::get('/table-tennis','ScoreSheetFrontController@tableTennisForm');

    Route::get('/chess-pdf','ScoreSheetFrontController@chessFormPdf');
    Route::get('/chess','ScoreSheetFrontController@chessForm');

    Route::get('/carom-pdf','ScoreSheetFrontController@carromFormPdf');
    Route::get('/carom','ScoreSheetFrontController@carromForm');

    Route::get('/all-other-csc-pdf','ScoreSheetFrontController@allOtherCscFormPdf');
    Route::get('/all-other-csc','ScoreSheetFrontController@allOtherCscForm');

    Route::get('/athletic-1-pdf','ScoreSheetFrontController@athleticType1FormPdf');
    Route::get('/athletic-1','ScoreSheetFrontController@athleticType1Form');


    Route::get('/athletic-2-pdf','ScoreSheetFrontController@athleticType2FormPdf');
    Route::get('/athletic-2','ScoreSheetFrontController@athleticType2Form');


    Route::get('/athletic-3-pdf','ScoreSheetFrontController@athleticType3FormPdf');
    Route::get('/athletic-3','ScoreSheetFrontController@athleticType3Form');

    Route::get('/athletic-4-pdf','ScoreSheetFrontController@athleticType4FormPdf');
    Route::get('/athletic-4','ScoreSheetFrontController@athleticType4Form');


});
