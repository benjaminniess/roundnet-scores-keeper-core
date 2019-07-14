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

Route::get('/games/live', 'GamesController@live')->middleware('auth');
Route::get('/games/{game}/start', 'GamesController@start')->middleware('auth');

Route::resource('games','GamesController')->middleware('auth');

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
Route::get('/friends', 'FriendsController@show')->middleware('auth');
Route::get('/friends/request/{user}', 'FriendsController@request')->middleware('auth');
Route::patch('/friends/{user}', 'FriendsController@update')->middleware('auth');
Route::post('/friends/send-invitation/', 'FriendsController@invite')->middleware('auth');
Route::post('/friends/search', 'FriendsController@search')->middleware('auth');
/*
|--------------------------------------------------------------------------
| End friends routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Start Users routes
|--------------------------------------------------------------------------
*/
Route::get('/user/account', 'UsersController@edit')->middleware('auth');
Route::patch('/user/{user}', 'UsersController@update')->middleware('auth');
Route::patch('/user/edit-password/{user}', 'UsersController@update_password')->middleware('auth');
Route::get('/user/stats', 'UsersController@stats')->middleware('auth');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
/*
|--------------------------------------------------------------------------
| End Users routes
|--------------------------------------------------------------------------
*/
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
