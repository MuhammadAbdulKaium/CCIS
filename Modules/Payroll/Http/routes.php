<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'payroll', 'namespace' => 'Modules\Payroll\Http\Controllers'], function()
{
    //Salary Head
    Route::get('/salary/head', 'SalaryHeadController@index');
    Route::get('/salary/create', 'SalaryHeadController@create');
    Route::post('/salary/store', 'SalaryHeadController@store');
    Route::get('/salary/edit/{id}', 'SalaryHeadController@edit');
    Route::post('/salary/update', 'SalaryHeadController@update');
    Route::post('/salary/delete', 'SalaryHeadController@destroy');

    //Salary Grade
    Route::get('/salary/grade', 'SalaryGradeController@index');
    Route::get('/salary/grade/create', 'SalaryGradeController@create');
    Route::post('/salary/grade/store', 'SalaryGradeController@store');
    Route::get('/salary/grade/edit/{id}', 'SalaryGradeController@edit');
    Route::post('/salary/grade/update', 'SalaryGradeController@update');
    Route::post('/salary/grade/delete', 'SalaryGradeController@destroy');

    //Salary Grade
    Route::get('/salary/scale', 'SalaryGradeController@index');
    Route::get('/salary/scale/create', 'SalaryScaleController@create');
    Route::post('/salary/scale/store', 'SalaryScaleController@store');
    Route::get('/salary/scale/edit/{id}', 'SalaryScaleController@edit');
    Route::post('/salary/scale/update', 'SalaryScaleController@update');
    Route::post('/salary/scale/delete', 'SalaryScaleController@destroy');
    //Salary Assign
    Route::get('/salary/assign', 'SalaryAssignController@index');
    Route::get('/salary/assign/create', 'SalaryAssignController@create');
    Route::post('/salary/assign/store', 'SalaryAssignController@store');
    Route::get('/salary/assign/edit/{id}', 'SalaryAssignController@edit');
    Route::post('/salary/assign/update', 'SalaryAssignController@update');
    Route::post('/salary/assign/delete', 'SalaryAssignController@destroy');

    // Bank
    Route::get('/bank/branch/search/{id}', 'BankDetailsController@searchBankBranch');

    //Salary Structure
    Route::get('/salary/structure', 'SalaryStructureController@index');
    Route::get('/salary/structure/edit/{id}', 'SalaryStructureController@editStructure');
    Route::post('/salary/structure/update', 'SalaryStructureController@updateStructure');
    Route::get('/salary/structure/history/{id}', 'SalaryStructureController@historyStructure');
    Route::get('/salary/structure/add/{id}', 'SalaryStructureController@addStructure');
    Route::post('/manage/head/structure', 'SalaryStructureController@getHeadStructure');
    Route::post('/salary/structure/generate', 'SalaryStructureController@generate');
    Route::post('/salary/structure/store', 'SalaryStructureController@storeSalaryStructure');

    // Salary Deduction
    Route::get('/salary/deduction', 'SalaryDeductionController@index');
    Route::post('/salary/deduct/store', 'SalaryDeductionController@store');

    Route::get('/salary/generate', 'SalaryGeneratorController@index');
    Route::post('/salary/generate/store', 'SalaryGeneratorController@store');
    Route::post('/salary/process/store', 'SalaryGeneratorController@processStore');

    // Bank
    Route::get('/bank', 'BankDetailsController@index');
    Route::get('/bank/create', 'BankDetailsController@create');
    Route::post('/bank/store', 'BankDetailsController@store');
    Route::get('/bank/edit/{id}', 'BankDetailsController@edit');
    Route::post('/bank/update', 'BankDetailsController@update');

    // Bank Branch
    Route::get('/bank/branch/create', 'BankDetailsController@createBranch');
    Route::post('/bank/branch/store', 'BankDetailsController@storeBranch');
    Route::get('/bank/branch/edit/{id}', 'BankDetailsController@editBranch');
    Route::post('/bank/branch/update', 'BankDetailsController@updateBranch');




});