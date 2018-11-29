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

Route::middleware(['auth:api'])->group(function () {
    
    Route::post('users/datatable', 'UserController@datatable')->name('admin.users.datatable');
    Route::post('users', 'UserController@store')->name('admin.users.store');
    Route::get('users/{user}/edit', 'UserController@edit')->name('admin.users.edit');
    Route::put('users/{user}', 'UserController@update')->name('admin.users.update');
    Route::delete('users/{user}', 'UserController@delete')->name('admin.users.delete');

    Route::post('news/datatable', 'NewsController@datatable')->name('admin.news.datatable');
    Route::get('news/create', 'NewsController@create')->name('admin.news.create');
    Route::post('news', 'NewsController@store')->name('admin.news.store');
    Route::get('news/{news}/edit', 'NewsController@edit')->name('admin.news.edit');
    Route::put('news/{news}', 'NewsController@update')->name('admin.news.update');
    Route::delete('news/{news}', 'NewsController@delete')->name('admin.news.delete');

    Route::post('permissions/datatable', 'PermissionController@datatable')->name('admin.permissions.datatable');
    Route::get('permissions/create', 'PermissionController@create')->name('admin.permissions.create');
    Route::post('permissions', 'PermissionController@store')->name('admin.permissions.store');
    Route::get('permissions/{permission}/edit', 'PermissionController@edit')->name('admin.permissions.edit');
    Route::put('permissions/{permission}', 'PermissionController@update')->name('admin.permissions.update');
    Route::delete('permissions/{permission}', 'PermissionController@delete')->name('admin.permissions.delete');
});