<?php

Route::get('/', function () {
    return view('welcome');
});


Route::get('cache.manifest', function() {
	return response()
		->view('cache')
		->header('Content-Type', 'text/cache-manifest');
});


Route::group(['middleware' => ['api'], 'prefix' => 'api/v1'], function () {
    Route::get('lists/{cart}/items', 'ItemController@index');
    Route::post('lists/{cart}/items', 'ItemController@store');
    Route::put('lists/{cart}/items/{item}', 'ItemController@update');
});


Route::get('/{list_slug}', function ($list_slug) {
    return view('list')->with('list_slug', $list_slug);
});
