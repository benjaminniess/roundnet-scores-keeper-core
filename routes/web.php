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

Route::get('/', function () {
    return view('home');
});

Route::get('/games', 'GamesController@index');

Route::get('/games/live', 'GamesController@live');

Route::post('/games', 'GamesController@store');

Route::get('/games/{id}', 'GamesController@show')->where('id', '[0-9+]');

Route::get('/games/create', 'GamesController@create');

Route::get('/friends', 'FriendsController@show');

Route::get('/account', function () {
	return view('account');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
