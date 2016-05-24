<?php

/** Customer Route  */
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root','Administrator','Dispatcher','Requisitor','Purchaser']], function () {
    Route::resource('customer', 'CustomerController', ['only' => ['index']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root','Administrator','Dispatcher','Requisitor','Purchaser']], function () {
    Route::resource('customer', 'CustomerController', ['only' => ['create']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root','Administrator','Dispatcher','Requisitor','Purchaser']], function () {
    Route::resource('customer', 'CustomerController', ['only' => ['store']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root','Administrator','Dispatcher','Requisitor','Purchaser']], function () {
    Route::resource('customer', 'CustomerController', ['only' => ['show']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root','Administrator','Dispatcher','Requisitor','Purchaser']], function () {
    Route::resource('customer', 'CustomerController', ['only' => ['edit']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root','Administrator','Dispatcher','Requisitor','Purchaser']], function () {
    Route::resource('customer', 'CustomerController', ['only' => ['update']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root','Administrator','Dispatcher','Requisitor','Purchaser']], function () {
    Route::resource('customer', 'CustomerController', ['only' => ['destroy']]);
});

/**Additional Routes**/
Route::get('customer/item/deleted', 'CustomerController@getDeleted');
Route::get('Customer/item/restore/{id}', 'CustomerController@restore');
