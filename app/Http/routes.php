<?php

Route::get('cache.manifest', function() {
	return response()
		->view('cache')
		->header('Content-Type', 'text/cache-manifest');
});


Route::group(['middleware' => ['api'], 'prefix' => 'api/v1'], function () {
    Route::get('lists/{cart}/items', 'ItemController@index');
    Route::post('lists/{cart}/items', 'ItemController@store');
    Route::put('lists/{cart}/items/{item}', 'ItemController@update');

    Route::get('lists', 'CartController@index');
    Route::post('lists', 'CartController@store');
});


Route::get('/{vue_capture?}', function () {
    return view('app');
})->where('vue_capture', '(?!_debugbar)(.*)');
