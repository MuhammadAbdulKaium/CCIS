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

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'canteen'], function () {
    // Menu & Recipe Routes Start

    Route::get('/menu-recipe', 'MenuRecipeController@index');

    // Menu Category Routes
    Route::post('/store/menu-category', 'MenuRecipeController@store');
    Route::get('/edit/menu-category/{id}', ['access' => ['canteen/menu-category.edit'], 'uses' => 'MenuRecipeController@edit']);
    Route::post('/update/menu-category/{id}', ['access' => ['canteen/menu-category.edit'], 'uses' => 'MenuRecipeController@update']);
    Route::get('/delete/menu-category/{id}', ['access' => ['canteen/menu-category.delete'], 'uses' => 'MenuRecipeController@destroy']);

    // Menu Routes
    Route::post('/store/menu', 'MenuRecipeController@storeMenu');
    Route::get('/edit/menu/{id}', ['access' => ['canteen/menu.edit'], 'uses' => 'MenuRecipeController@editMenu']);
    Route::post('/update/menu/{id}', ['access' => ['canteen/menu.edit'], 'uses' => 'MenuRecipeController@updateMenu']);
    Route::get('/delete/menu/{id}', ['access' => ['canteen/menu.delete'], 'uses' => 'MenuRecipeController@deleteMenu']);
    Route::get('/menu/history/{id}', ['access' => ['canteen/menu.history'], 'uses' => 'MenuRecipeController@menuHistory']);

    // Menu Recipe Routes
    Route::post('/store/menu-recipe', 'MenuRecipeController@storeMenuRecipe');
    Route::get('/edit/menu-recipe/{id}', ['access' => ['canteen/menu-recipe.edit'], 'uses' => 'MenuRecipeController@editMenuRecipe']);
    Route::post('/update/menu-recipe/{id}', ['access' => ['canteen/menu-recipe.edit'], 'uses' => 'MenuRecipeController@updateMenuRecipe']);
    Route::get('/assign/recipe/items/{id}', ['access' => ['canteen/menu-recipe.assign'], 'uses' => 'MenuRecipeController@assignRecipeItems']);
    Route::get('/delete/recipe/items/{id}', ['access' => ['canteen/menu-recipe.delete'], 'uses' => 'MenuRecipeController@destroyRecipeItems']);

    // Menu Recipe Ajax Routes
    Route::get('/assign/item/to/recipe', 'MenuRecipeController@assignItemToRecipe');
    Route::get('/update/recipe/item', 'MenuRecipeController@updateRecipeItem');
    Route::get('/remove/recipe/item', 'MenuRecipeController@removeRecipeItem');
    Route::get('/get/recipe/stock-items', 'MenuRecipeController@getRecipeStockItems');

    // Menu & Recipe Routes End




    // Stock In Routes Start
    Route::get('/stock-in/{id?}', ['access' => ['canteen/stock-in', 'canteen/stock-in.edit'], 'uses' => 'CanteenStockInController@index']);
    Route::post('/stock-in', 'CanteenStockInController@store');
    Route::post('/update/stock-in/{id}', ['access' => ['canteen/stock-in.edit'], 'uses' => 'CanteenStockInController@update']);
    Route::get('/delete/stock-in/{id}', ['access' => ['canteen/stock-in.delete'], 'uses' => 'CanteenStockInController@destroy']);

    //Stock In Ajax Routes
    Route::get('/get/menus/from/category', 'CanteenStockInController@getMenusFromCategory');
    Route::get('/get/uom/from/menus', 'CanteenStockInController@getUomFromMenus');

    // Stock In Routes End




    // Transaction Routes Start
    Route::get('/transaction', 'CanteenTransactionController@index');
    Route::post('/store/transaction', 'CanteenTransactionController@store');
    Route::get('/customer-processing', 'CanteenTransactionController@customerProcessing');
    Route::get('/transaction/history/{type}/{id}', ['access' => ['canteen/transaction.history'], 'uses' => 'CanteenTransactionController@transactionHistory']);

    //Transaction Ajax Routes
    Route::get('/transaction/get/customer-info', 'CanteenTransactionController@getCustomerInfo');
    Route::get('/search/transactions', 'CanteenTransactionController@searchTransactions');

    // Transaction Routes End

});
