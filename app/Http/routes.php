<?php

Route::get('/', function () {
    return view('welcome');
});


Route::get('cache.manifest', function() {
	return response()
		->view('cache')
		->header('Content-Type', 'text/cache-manifest');
});


Route::get('/{list_slug}', function ($list_slug) {
    return view('list')->with('list_slug', $list_slug);
});


Route::get('/api/v1/lists/{list_slug}/items', function($list_slug) {
	return [
		['id' => 1, 'name' => 'tomates', 'completed' => false],
		['id' => 2, 'name' => 'leche', 'completed' => false],
		['id' => 3, 'name' => 'yogures', 'completed' => false],
		['id' => 4, 'name' => 'galletas de chocolate', 'completed' => false],
		['id' => 5, 'name' => 'cepillo de dientes', 'completed' => false],
		['id' => 6, 'name' => 'helados', 'completed' => false],
		['id' => 7, 'name' => 'aceite', 'completed' => false],
		['id' => 8, 'name' => 'aguacates', 'completed' => false],
		['id' => 9, 'name' => 'kiwis', 'completed' => false],
		['id' => 10, 'name' => 'agua', 'completed' => false],
		['id' => 11, 'name' => 'plátanos', 'completed' => false],
		['id' => 12, 'name' => 'papas', 'completed' => true]
	];
});
