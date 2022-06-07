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

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'mess'], function () {
    Route::get('/', 'MessController@index');

    // Mess Table Routes
    Route::get('/table/print','MessTableController@print');
    Route::get('/table', 'MessTableController@index');
    Route::get('/create/table', ['access' => ['mess/table.create'], 'uses' => 'MessTableController@create']);
    Route::post('/store/table', ['access' => ['mess/table.create'], 'uses' => 'MessTableController@store']);
    Route::get('/edit/table/{id}', ['access' => ['mess/table.edit'], 'uses' => 'MessTableController@edit']);
    Route::post('/update/table/{id}', ['access' => ['mess/table.edit'], 'uses' => 'MessTableController@update']);
    Route::get('/table/history/{id}', ['access' => ['mess/table.history'], 'uses' => 'MessTableController@history']);
    Route::get('/delete/table/{id}', ['access' => ['mess/table.delete'], 'uses' => 'MessTableController@destroy']);

    // Mess Table Ajax Routes
    Route::get('/get/persons/from/personType', 'MessTableController@getPersonsFromPersonType');
    Route::get('/get/all/persons/from/personType', 'MessTableController@getAllPersonsFromPersonType');
    Route::get('/assign/person/to/seat', 'MessTableController@assignPersonToSeat');
    Route::get('/get/mess/table/view', 'MessTableController@getMessTableView');
    Route::get('/get/person/details', 'MessTableController@getPersonDetails');
    Route::get('/get/mess/table/seats', 'MessTableController@getMessTableSeats');
    Route::get('/remove/person/from/seat', 'MessTableController@removePersonFromSeat');
    Route::get('/search/table/by/person', 'MessTableController@searchTableByPerson');


    // Mess Food Menu Routes
    Route::get('/food-menu', 'FoodMenuController@index');
    Route::post('/food-menu/store/category', 'FoodMenuController@store');
    Route::post('/food-menu/store/menu', 'FoodMenuController@storeMenu');
    Route::post('/food-menu/store/menu/item', 'FoodMenuController@storeMenuItem');
    Route::get('/food-menu/category/delete/{id}', ['access' => ['mess/food-menu-category.delete'], 'uses' => 'FoodMenuController@destroy']);

    // Mess Food Menu Modal Routes
    Route::get('/food-menu/category/edit/{id}', ['access' => ['mess/food-menu-category.edit'], 'uses' => 'FoodMenuController@edit']);
    Route::get('/food-menu/edit/{id}', ['access' => ['mess/food-menu.edit'], 'uses' => 'FoodMenuController@menuEdit']);
    Route::get('/food-menu/item/edit/{id}', ['access' => ['mess/food-menu-item.edit'], 'uses' => 'FoodMenuController@menuItemEdit']);
    Route::get('/food-menu/assign-item/view/{id}', ['access' => ['mess/food-menu.assign'], 'uses' => 'FoodMenuController@assignItemView']);
    Route::post('/food-menu/category/update/{id}', ['access' => ['mess/food-menu-category.edit'], 'uses' => 'FoodMenuController@update']);
    Route::post('/food-menu/update/{id}', ['access' => ['mess/food-menu.edit'], 'uses' => 'FoodMenuController@updateMenu']);
    Route::post('/food-menu/item/update/{id}', ['access' => ['mess/food-menu-item.edit'], 'uses' => 'FoodMenuController@updateMenuItem']);
    Route::post('/food-menu/assign-item/to/menu/{id}', ['access' => ['mess/food-menu-item.assign'], 'uses' => 'FoodMenuController@assignItemToMenu']);
    Route::get('/food-menu/delete/{id}', ['access' => ['mess/food-menu.delete'], 'uses' =>  'FoodMenuController@menuDelete']);
    Route::get('/food-menu/item/delete/{id}', ['access' => ['mess/food-menu-item.delete'], 'uses' =>  'FoodMenuController@menuItemDelete']);


    // Mess Food Menu Schedule Routes
    Route::get('/food-menu-schedule', 'FoodMenuScheduleController@index');
    Route::get('/print/food-menu-schedule', 'FoodMenuScheduleController@printSchedule');

    // Mess Food Menu Schedule Ajax Routes
    Route::get('/food-menu-schedule/table', 'FoodMenuScheduleController@foodMenuScheduleTable');
    Route::get('/get/menu/from/category', 'FoodMenuScheduleController@getMenuFromCategory');
    Route::get('/get/form-designation/from/class-department', 'FoodMenuScheduleController@getFormDesignationFromClassDepartment');
    Route::get('/get/strength/from/form-designation', 'FoodMenuScheduleController@getStrengthFormDesignation');
    Route::get('/save/food-menu/schedule', 'FoodMenuScheduleController@saveFoodMenuSchedule');
});
