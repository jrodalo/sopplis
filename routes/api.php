<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
/* Con autenticación */
Route::group(['middleware' => ['auth:api']], function() {

    Route::get('/lists', 'CartController@index');
    Route::post('/lists', 'CartController@store');

    Route::get('/lists/{cart}/items', 'ItemController@index');
    Route::post('/lists/{cart}/items', 'ItemController@store');
    Route::delete('/lists/{cart}/items', 'ItemController@delete');
    Route::put('/lists/{cart}/items/{item}', 'ItemController@update');

    Route::get('/lists/{cart}/favorite', 'FavoriteController@index');
    Route::put('/lists/{cart}/favorite', 'FavoriteController@update');
    Route::delete('/lists/{cart}/favorite', 'FavoriteController@delete');

    Route::put('/users', 'UserController@update');

});

/* Sin autenticación */
Route::post('/users', 'UserController@store');

Route::get('/prueba', function() {
    return [
                ['name' => '1'],
                ['name' => '2'],
                ['name' => '3']
            ];
});
