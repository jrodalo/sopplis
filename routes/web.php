<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/webhooks/lists', 'WebhookController@store');

Route::get('/{vue_capture?}', function () {
    return view('app');
})->where('vue_capture', '(.*)'); // '(?!_debugbar)(.*)'
