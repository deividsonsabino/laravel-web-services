<?php
/* 
Route::get('categories', 'Api\CategoryController@index');

Route::post('categories', 'Api\CategoryController@store');

Route::put('categories/{id}', 'Api\CategoryController@update');

Route::delete('categories/{id}', 'Api\CategoryController@destroy');
 */
 Route::group(['prefix' => 'v1','namespace' => 'Api\v1'], function (){

    Route::get('categories/{id}/products', 'CategoryController@products');

    Route::apiResource('categories', 'CategoryController');
   
    Route::apiResource('products', 'ProductController');
 });


