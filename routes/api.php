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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['cors'])->group(function () {
    
    Route::post('users/datatable', 'UserController@datatable');
    Route::post('users', 'UserController@store');
    Route::get('users/{user}/edit', 'UserController@edit');
    Route::put('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@delete');

    Route::post('news/datatable', 'NewsController@datatable');
    Route::get('news/create', 'NewsController@create');
    Route::post('news', 'NewsController@store');
    Route::get('news/{news}/edit', 'NewsController@edit');
    Route::put('news/{news}', 'NewsController@update');
    Route::delete('news/{news}', 'NewsController@delete');
});