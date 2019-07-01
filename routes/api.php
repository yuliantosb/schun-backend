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
    Route::get('role/{id}', 'Api\RoleController@show');
    Route::put('role/{id}', 'Api\RoleController@update');
    Route::post('role', 'Api\RoleController@store');
    Route::delete('role/{id}', 'Api\RoleController@destroy');
    // Permission
    Route::get('permission', 'Api\PermissionController@index');
    Route::get('permission/parent', 'Api\PermissionController@getParent');
    Route::post('permission', 'Api\PermissionController@store');
    Route::get('permission/{id}', 'Api\PermissionController@show');
    Route::put('permission/{id}', 'Api\PermissionController@update');
    Route::delete('permission/{id}', 'Api\PermissionController@destroy');
    // Logout
    Route::post('logout', 'Api\Auth\LoginController@logout');
     //Store
     Route::get('store', 'Api\StoreController@index');
     Route::post('store', 'Api\StoreController@store');
     Route::get('store/{id}', 'Api\StoreController@show');
     Route::put('store/{id}', 'Api\StoreController@update');
     Route::delete('store/{id}', 'Api\StoreController@destroy');
     //Employee
     Route::get('employee', 'Api\EmployeeController@index');
     Route::post('employee', 'Api\EmployeeController@store');
     Route::get('employee/role', 'Api\EmployeeController@role');
     Route::get('employee/{id}', 'Api\EmployeeController@show');
     Route::put('employee/{id}', 'Api\EmployeeController@update');
     Route::delete('employee/{id}', 'Api\EmployeeController@destroy');
     //  customer
     Route::get('customer', 'Api\CustomerController@index');
     Route::post('customer', 'Api\CustomerController@store');
     Route::get('customer/{id}', 'Api\CustomerController@show');
     Route::put('customer/{id}', 'Api\CustomerController@update');
     Route::delete('customer/{id}', 'Api\CustomerController@destroy');
      //Expense
     Route::get('expense', 'Api\ExpenseController@index');
     Route::post('expense', 'Api\ExpenseController@store');
     Route::get('expense/user', 'Api\ExpenseController@user');
     Route::get('expense/{id}', 'Api\ExpenseController@show');
     Route::put('expense/{id}', 'Api\ExpenseController@update');
     Route::delete('expense/{id}', 'Api\ExpenseController@destroy');
       //Category
     Route::get('category', 'Api\CategoryController@index');
     Route::post('category', 'Api\CategoryController@store');
     Route::get('category/parent', 'Api\CategoryController@parent');
     Route::get('category/{id}', 'Api\CategoryController@show');
     Route::put('category/{id}', 'Api\CategoryController@update');
     Route::delete('category/{id}', 'Api\CategoryController@destroy');
       //supplier
     Route::get('supplier', 'Api\SupplierController@index');
     Route::post('supplier', 'Api\SupplierController@store');
     Route::get('supplier/{id}', 'Api\SupplierController@show');
     Route::put('supplier/{id}', 'Api\SupplierController@update');
     Route::delete('supplier/{id}', 'Api\SupplierController@destroy');
    //products
    Route::get('products/category', 'Api\ProductsController@category');
    Route::get('products', 'Api\ProductsController@index');
    Route::post('products', 'Api\ProductsController@store');
    Route::get('products/{id}', 'Api\ProductsController@show');
    Route::put('products/{id}', 'Api\ProductsController@update');
    Route::delete('products/{id}', 'Api\ProductsController@destroy');
    //sales
    Route::get('sales', 'Api\SalesController@index');
    Route::post('sales', 'Api\SalesController@store');
    Route::get('sales/{id}', 'Api\SalesController@show');
    Route::put('sales/{id}', 'Api\SalesController@update');
    Route::delete('sales/{id}', 'Api\SalesController@destroy');
    //shippingfee
    Route::get('shippingfee', 'Api\ShippingfeeController@index');
    Route::post('shippingfee', 'Api\ShippingfeeController@store');
    Route::get('shippingfee/{id}', 'Api\ShippingfeeController@show');
    Route::put('shippingfee/{id}', 'Api\ShippingfeeController@update');
    Route::delete('shippingfee/{id}', 'Api\ShippingfeeController@destroy');
    //grouppacket
    Route::get('grouppacket', 'Api\GrouppacketController@index');
    Route::post('grouppacket', 'Api\GrouppacketController@store');
    Route::get('grouppacket/{id}', 'Api\GrouppacketController@show');
    Route::put('grouppacket/{id}', 'Api\GrouppacketController@update');
    Route::delete('grouppacket/{id}', 'Api\GrouppacketController@destroy');
    //grouppacketdetails
    Route::get('grouppacketdetails', 'Api\GrouppacketdetailsController@index');
    Route::post('grouppacketdetails', 'Api\GrouppacketdetailsController@store');
    Route::get('grouppacketdetails/{id}', 'Api\GrouppacketdetailsController@show');
    Route::put('grouppacketdetails/{id}', 'Api\GrouppacketdetailsController@update');
    Route::delete('grouppacketdetails/{id}', 'Api\GrouppacketdetailsController@destroy');
    // discount
    Route::get('discount', 'Api\DiscountController@index');
    Route::post('discount', 'Api\DiscountController@store');
    Route::get('discount/{id}', 'Api\DiscountController@show');
    Route::put('discount/{id}', 'Api\DiscountController@update');
    Route::delete('discount/{id}', 'Api\DiscountController@destroy');
    // settings
    Route::put('setting', 'Api\SettingController@update');
});

Route::get('setting', 'Api\SettingController@index');
// Login
Route::post('login', 'Api\Auth\LoginController@login');
Route::post('forgot-password', 'Api\Auth\LoginController@forgot');
Route::post('reset', 'Api\Auth\LoginController@reset');