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
    // Role
    Route::get('role', 'Api\RoleController@index');
    Route::get('role/permissions', 'Api\RoleController@getPermissions');
    Route::post('role', 'Api\RoleController@store');
    // Permission
    Route::get('permission', 'Api\PermissionController@index');
    Route::get('permission/parent', 'Api\PermissionController@getParent');
    Route::post('permission', 'Api\PermissionController@store');
    Route::get('permission/{id}', 'Api\PermissionController@show');
    Route::put('permission/{id}', 'Api\PermissionController@update');
    Route::delete('permission/{id}', 'Api\PermissionController@destroy');
    // Logout
    Route::post('logout', 'Api\Auth\LoginController@logout');
});

// Login
Route::post('login', 'Api\Auth\LoginController@login');