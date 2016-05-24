<?php

/** Department Route  */
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('department', 'DepartmentController', ['only' => ['index']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('department', 'DepartmentController', ['only' => ['create']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('department', 'DepartmentController', ['only' => ['store']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('department', 'DepartmentController', ['only' => ['show']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('department', 'DepartmentController', ['only' => ['edit']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('department', 'DepartmentController', ['only' => ['update']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('department', 'DepartmentController', ['only' => ['destroy']]);
});

/**Additional Routes**/
Route::get('department/stock/deleted', 'DepartmentController@getDeleted');
Route::get('department/stock/restore/{id}', 'DepartmentController@restore');
Route::get('department/stock/export', 'DepartmentController@export');
Route::get('department/{id}', 'DepartmentController@show');
