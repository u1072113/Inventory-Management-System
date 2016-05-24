<?php

/** Staff Route  */
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('staff', 'StaffController', ['only' => ['index']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('staff', 'StaffController', ['only' => ['create']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('staff', 'StaffController', ['only' => ['store']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('staff', 'StaffController', ['only' => ['show']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('staff', 'StaffController', ['only' => ['edit']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('staff', 'StaffController', ['only' => ['update']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('staff', 'StaffController', ['only' => ['destroy']]);
});

/**Additional Routes**/
Route::get('staff/get/all', 'StaffController@getStaff');
Route::post('staff/create/ajax', 'StaffController@createStaff');
