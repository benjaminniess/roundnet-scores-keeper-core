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

Route::get('/games', function () {

	$games = [
		[
			'id' => 1,
			'score' => '21 - 3',
			'date' => '22/05/2019',
		],
		[
			'id' => 2,
			'score' => '10 - 21',
			'date' => '23/05/2019',
		],
		[
			'id' => 3,
			'score' => '24 - 22',
			'date' => '24/05/2019',
		],
	];

	return view('games', [
		'games' => $games,
	]);
});

Route::get('/games/{n}', function () {
	return view('games');
});

Route::get('/friends', function () {
	return view('friends');
});

Route::get('/account', function () {
	return view('account');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
