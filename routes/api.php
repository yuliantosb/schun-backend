<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:api'])->group(function(){
    // Route Untuk Items
    Route::get('items', 'Api\ItemController@index');

    // Route Untuk Batching Plan
    Route::get('batchings', 'Api\BatchingController@index');

    // Route Untuk Customer
    Route::get('customers', 'Api\CustomerController@index');

    // Route Untuk StockIn
    Route::get('stock-in', 'Api\StockinController@index');

    // Route Untuk Stock Out
    Route::get('stock-out', 'Api\StockoutController@index');
});

Route::post('login', 'Api\Auth\LoginController@login');
Route::middleware('auth:api')->post('logout', 'Api\Auth\LoginController@logout');