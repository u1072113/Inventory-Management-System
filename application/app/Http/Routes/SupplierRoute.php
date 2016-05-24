<?php

/** Supplier  Route  */
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('supplier', 'SupplierController', ['only' => ['index']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('supplier', 'SupplierController', ['only' => ['create']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('supplier', 'SupplierController', ['only' => ['store']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('supplier', 'SupplierController', ['only' => ['show']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('supplier', 'SupplierController', ['only' => ['edit']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('supplier', 'SupplierController', ['only' => ['update']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('supplier', 'SupplierController', ['only' => ['destroy']]);
});

/**Additional Routes**/
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::get('supplier/stock/deleted', 'SupplierController@getDeleted');
    Route::get('supplier/stock/restore/{id}', 'SupplierController@restore');
    Route::get('supplier/stock/export', 'SupplierController@export');
});
