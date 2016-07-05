<?php

Route::get('/', function () {
    return view('welcome');
});


Route::get('/{list_slug}', function ($list_slug) {
    return view('list')->with('list_slug', $list_slug);
});


Route::get('/api/v1/lists/{list_slug}/items', function($list_slug) {
	return [
		['name' => 'tomates', 'completed' => false],
		['name' => 'leche', 'completed' => false],
		['name' => 'yogures', 'completed' => false],
		['name' => 'galletas de chocolate', 'completed' => false],
		['name' => 'cepillo de dientes', 'completed' => false],
		['name' => 'helados', 'completed' => false],
		['name' => 'aceite', 'completed' => false],
		['name' => 'aguacates', 'completed' => false],
		['name' => 'kiwis', 'completed' => false],
		['name' => 'agua', 'completed' => false],
		['name' => 'plátanos', 'completed' => false],
		['name' => 'papas', 'completed' => true]
	];
});
