<?php

Route::group(['middleware' => ['web'], 'prefix' => 'onlineacademics', 'namespace' => 'Modules\OnlineAcademics\Http\Controllers'], function()
{
    Route::get('/', 'OnlineAcademicsController@index');
    Route::get('/onlineacademic/{classtopic}', ['access'=>['onlineacademics'], 'uses'=>'OnlineAcademicsController@index']);
    Route::post('/onlineacademic/getTopicInfo/', 'OnlineAcademicsController@getTopicInfo');
    Route::any('onlineacademic/edit/{id}', ['access'=>['onlineacademics.classtopic.edit'], 'uses'=>'OnlineAcademicsController@edit']);
    Route::post('/onlineacademic/updateTopicInfo/{id}', ['access'=>['onlineacademics.classtopic.edit'], 'uses'=>'OnlineAcademicsController@update']);
    Route::any('onlineacademic/destroy/{id}', ['access'=>['onlineacademics.classtopic.delete'], 'uses'=>'OnlineAcademicsController@destroy']);
    Route::get('onlineacademic/find/topic', 'OnlineAcademicsController@findtopic');
    Route::post('onlineacademic/ClassHistory', 'OnlineAcademicsController@ClassHistory');
    Route::post('onlineacademic/onlineclass', 'OnlineAcademicsController@StoreClassTopic');
    Route::get('onlineacademic/find/ajax_topic', 'OnlineAcademicsController@ajax_topic');


    Route::get('/find/teacher', 'OnlineAcademicsController@findTeacher');
    Route::get('/find/ajax_teacher_topic', 'OnlineAcademicsController@ajax_teacher_topic');

    Route::post('onlineacademic/ClassSchedule', 'OnlineAcademicsController@ClassSchedule');
    Route::post('onlineacademic/OnlineScheduleClass', 'OnlineAcademicsController@OnlineScheduleClass');

    Route::post('onlineacademic/OnlineScheduleClass', 'OnlineAcademicsController@OnlineScheduleClass');
    

    Route::get('onlineacademic/LiveClassScheduled/{ScheduleId}', 'OnlineAcademicsController@LiveClassScheduled');

    Route::get('onlineacademic/LiveClassScheduledbroadcast/{ScheduleId}', 'OnlineAcademicsController@LiveClassScheduledbroadcast');
       
    Route::get('onlineclass/onlineclass_conducts', 'OnlineAcademicsController@onlineclass_condduct');

     Route::get('onlineclass/onlineclass_conducts_std_teach', 'OnlineAcademicsController@onlineclass_condduct_std_teach');


    
});
