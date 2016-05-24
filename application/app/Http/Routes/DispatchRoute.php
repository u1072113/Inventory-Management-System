<?php

/** Dispatch Route  */
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser', 'Elevated User']], function () {
    Route::resource('dispatch', 'DispatchController', ['only' => ['index']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('dispatch', 'DispatchController', ['only' => ['create']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('dispatch', 'DispatchController', ['only' => ['store']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('dispatch', 'DispatchController', ['only' => ['show']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('dispatch', 'DispatchController', ['only' => ['edit']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('dispatch', 'DispatchController', ['only' => ['update']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('dispatch', 'DispatchController', ['only' => ['destroy']]);
});

/**Additional Routes**/
Route::get('dispatch/stock/deleted', 'DispatchController@getDeleted');
Route::get('dispatch/stock/defective', 'DispatchController@getDefective');
Route::get('dispatch/stock/restore/{id}', 'DispatchController@restore');
Route::get('dispatch/stock/export', 'DispatchController@export');
Route::get('dispatch/{id}', 'DispatchController@show');
