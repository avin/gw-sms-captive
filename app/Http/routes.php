<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'key.index', 'uses' => 'KeyController@index']);
Route::post('/', ['as' => 'key.verify', 'uses' => 'KeyController@verify']);

Route::post('get', ['as' => 'key.getKey.process', 'uses' => 'KeyController@getKeyProcess']);

/**
 * Логи
 */
Route::group(['prefix' => 'log'], function () {
    Route::get('/', ['as' => 'log.index', 'uses' => 'LogController@index']);
    Route::get('/{sessionId}', ['as' => 'log.get', 'uses' => 'LogController@get']);

});