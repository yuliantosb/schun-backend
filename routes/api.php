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
    // Route Untuk Item
    Route::get('items', 'Api\ItemController@index');
    // Route Untuk Input Data Item
    Route::post('items/store', 'Api\ItemController@store');
    // Route Untuk Update Data Item
    Route::get('items/{id}/show', 'Api\ItemController@show');
    Route::put('items/{id}/update', 'Api\ItemController@update');
    // Route Untuk Delete Data Item
    Route::delete('items/{id}/delete', 'Api\ItemController@delete');

    // Route Untuk Batching Plan
    Route::get('batchings', 'Api\BatchingController@index');
    // Route Untuk Input Data Batching Plan
    Route::post('batchings/store', 'Api\BatchingController@store');
    // Route Untuk Update Data Batching Plan
    Route::get('batchings/{id}/show', 'Api\BatchingController@show');
    Route::put('batchings/{id}/update', 'Api\BatchingController@update');
    // Route Untuk Delete Data Batching Plan
    Route::delete('batchings/{id}/delete', 'Api\BatchingController@delete');

    // Route Untuk Customer
    Route::get('customers', 'Api\CustomerController@index');
    // Route Untuk Input Data Customer
    Route::post('customers/store', 'Api\CustomerController@store');
    // Route Untuk Mengupdate Data Customer
    Route::get('customers/{id}/show', 'Api\CustomerController@show');
    Route::put('customers/{id}/update', 'Api\CustomerController@update');
    // Route Untuk Delete Data Customer
    Route::delete('customers/{id}/delete', 'Api\CustomerController@delete');

    // Route Untuk Stock In
    Route::get('stock-in', 'Api\StockinController@index');
    // Route Untuk Input Data Stock In
    Route::post('stock-in/store', 'Api\StockinController@store');
    // Route Untuk Mengupdate Data Stock In
    Route::get('stock-in/{id}/show', 'Api\StockinController@show');
    Route::put('stock-in/{id}/update', 'Api\StockinController@update');
    // Route Untuk Delete Data Stock In
    Route::delete('stock-in/{id}/delete', 'Api\StockinController@delete');


    // Route Untuk Stock Out
    Route::get('stock-out', 'Api\StockoutController@index');
    // Route Untuk Input Data Stock Out
    Route::post('stock-out/store', 'Api\StockoutController@store');
    // Router Untuk Mengupdate Data Stock Out
    Route::get('stock-out/{id}/show', 'Api\StockoutController@show');
    Route::put('stock-out/{id}/update', 'Api\StockoutController@update');
    // Route Untuk Delete Data Stock Out
    Route::delete('stock-out/{id}/delete', 'Api\StockoutController@delete');
});

Route::post('login', 'Api\Auth\LoginController@login');
Route::middleware('auth:api')->post('logout', 'Api\Auth\LoginController@logout');