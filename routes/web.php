<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'WalletHistController@welcome');
Route::post('/client/{client_id}', 'WalletHistController@report');
Route::get('/client/{client_id}', 'WalletHistController@report');
