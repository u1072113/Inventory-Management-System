<?php

/** Product Route  */
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser','User']], function () {
    Route::resource('product', 'ProductController', ['only' => ['index']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('product', 'ProductController', ['only' => ['create']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('product', 'ProductController', ['only' => ['store']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('product', 'ProductController', ['only' => ['show']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('product', 'ProductController', ['only' => ['edit']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Requisitor', 'Purchaser']], function () {
    Route::resource('product', 'ProductController', ['only' => ['update']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Dispatcher', 'Purchaser']], function () {
    Route::resource('product', 'ProductController', ['only' => ['destroy']]);
});

/**Additional Routes**/
Route::group(['middleware' => ['auth']], function () {
    Route::get('product/stock/finished', 'ProductController@getOutOfStock');

    Route::get('product/category/show', 'ProductController@showCategories');
    Route::post('product/category/add', 'ProductController@addCategories');

    Route::get('product/category/get', 'ProductController@categoryGet');
    Route::get('product/category/update', 'ProductController@categoryUpdate');
    Route::delete('product/category/delete/{id}', 'ProductController@categoryDelete');

    Route::get('product/stock/deleted', 'ProductController@getDeleted');
    Route::get('product/stock/warning', 'ProductController@getBelowLevels');
    Route::get('product/stock/restore/{id}', 'ProductController@restore');
    Route::post('product/upload/photo', 'ProductController@uploadPhoto');
    Route::get('product/stock/export', 'ProductController@export');
    Route::get('product/stock/products', 'ProductController@getProducts');
    Route::get('product/stock/import', 'ProductController@import');
    Route::post('product/stock/import', 'ProductController@import');
});