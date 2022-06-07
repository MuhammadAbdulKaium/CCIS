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

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'userrolemanagement'], function () {
    Route::get('/upload-routes', 'UserRoleManagementController@index');
    Route::post('/upload-routes/post', 'UserRoleManagementController@store');
    Route::get('/roll-permissions', 'UserRoleManagementController@rolePermissionsView');
    Route::get('/user-permissions', 'UserRoleManagementController@userPermissionsView');
    Route::post('/search-user', 'UserRoleManagementController@searchUser');
    Route::get('/menu-accessibility/{type}/{id}', 'UserRoleManagementController@menuAccessibility');
    Route::post('/save/menu-accessibility', 'UserRoleManagementController@saveMenuAccessibility');

    // Ajax Routes
    Route::get('/get-employees/from-designation', 'UserRoleManagementController@getEmployeesFromDesignation');
    Route::get('/get-students/from-section', 'UserRoleManagementController@getStudentsFromSection');
});
