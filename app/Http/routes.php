<?php

Route::get('cache.manifest', function() {
	return response()
		->view('cache')
		->header('Content-Type', 'text/cache-manifest');
});


Route::group(['prefix' => 'api/v1'], function () {

	Route::group(['middleware' => ['throttle:100,1', 'auth:api']], function() {

	    Route::get('lists/{cart}/items', 'ItemController@index');
	    Route::post('lists/{cart}/items', 'ItemController@store');
	    Route::put('lists/{cart}/items/{item}', 'ItemController@update');

	    Route::get('lists', 'CartController@index');
	    Route::post('lists', 'CartController@store');
	});

	Route::group(['middleware' => ['throttle:5,1']], function() {

    	Route::get('users', 'UserController@readToken');
    	Route::post('users', 'UserController@store');
	});
});


Route::get('/{vue_capture?}', function () {
    return view('app');
})->where('vue_capture', '(?!_debugbar)(.*)');
