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

Route::get('/', 'HomeController@index');

/*
|--------------------------------------------------------------------------
| Start Games routes
|--------------------------------------------------------------------------
*/

Route::get('/games/live', 'GamesController@live');
Route::get('/games/{game}/start', 'GamesController@start');

Route::resource('games','GamesController');

/*
|--------------------------------------------------------------------------
| End Games routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Start Friends routes
|--------------------------------------------------------------------------
*/
Route::get('/friends', 'FriendsController@show');
Route::get('/friends/request/{user}', 'FriendsController@request');
Route::patch('/friends/{user}', 'FriendsController@update');
Route::post('/friends/search', 'FriendsController@search');
/*
|--------------------------------------------------------------------------
| End friends routes
|--------------------------------------------------------------------------
*/

Route::get('/account', function () {
	return view('account');
});

Auth::routes();
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
