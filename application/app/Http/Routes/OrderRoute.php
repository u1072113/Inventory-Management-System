<?php

/** Order Route  */
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('order', 'PurchaseOrderController', ['only' => ['index']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('order', 'PurchaseOrderController', ['only' => ['create']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('order', 'PurchaseOrderController', ['only' => ['store']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('order', 'PurchaseOrderController', ['only' => ['show']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('order', 'PurchaseOrderController', ['only' => ['edit']]);
});
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('order', 'PurchaseOrderController', ['only' => ['update']]);
});

Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::resource('order', 'PurchaseOrderController', ['only' => ['destroy']]);
});

/**Additional Routes**/
Route::group(['middleware' => ['auth', 'roles'], 'roles' => ['Root', 'Administrator', 'Purchaser']], function () {
    Route::get('order/api/reorder/{id}', 'PurchaseOrderController@getRestock');
    Route::get('order/restock/po/{id}', 'PurchaseOrderController@getRestockFromPurchaseOrder');
    Route::get('order/restock/undelivered', 'PurchaseOrderController@getRestockFromPurchaseOrder');
    Route::get('order/restock/po/partDelivery', 'PurchaseOrderController@getRestockFromPurchaseOrder');
    Route::get('order/restock/po/delivered/{id}', 'PurchaseOrderController@getRestockFromPurchaseOrder');
    Route::post('order/restock/po', 'PurchaseOrderController@postRestockFromPurchaseOrder');
    Route::get('order/print/po/{id}', 'PurchaseOrderController@printLpo');
    Route::post('order/restock/status/{status}', 'PurchaseOrderController@deliveryStatus');
    Route::get('order/list/deleted', 'PurchaseOrderController@getDeleted');
    Route::get('order/list/restore', 'PurchaseOrderController@restore');

});

