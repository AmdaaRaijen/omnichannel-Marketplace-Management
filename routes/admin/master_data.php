<?php


/*
|--------------------------------------------------------------------------
| Master Data Routes
|--------------------------------------------------------------------------
|
| Here is where you can register master data related routes for your application.
|
*/

use App\Http\Controllers\OrderController;

Route::controller(OrderController::class)->middleware('can:view_orders')->prefix('order')->name('order.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('get-data', 'getData')->name('getdata');
    Route::get('create', 'createPage')->name('createPage');
    Route::post('create', 'createData')->name('create');
    Route::post('{id}/update', 'updateData')->name('update');
    Route::delete('{id}/delete', 'deleteData')->name('delete');
});

Route::controller(ProductController::class)->middleware('can:view_product')->prefix('product')->name('product.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('get-data', 'getData')->name('getdata');
    Route::post('create', 'createData')->name('create');
    Route::post('{id}/update', 'updateData')->name('update');
    Route::delete('{id}/delete', 'deleteData')->name('delete');
});