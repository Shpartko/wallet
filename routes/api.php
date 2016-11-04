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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::put('/client', 'ClientController@create');
Route::put('/exchange_rate', 'ExchangeRateController@add');
Route::post('/client/{name}', 'TransactionController@clientTransaction');
Route::post('/wallet/{wallet}', 'TransactionController@walletTransaction');
Route::post('/client/{name_from}/{name_to}', 'TransactionController@clientTransferTransaction');
Route::post('/wallet/{wallet_from}/{wallet_to}', 'TransactionController@walletTransferTransaction');
