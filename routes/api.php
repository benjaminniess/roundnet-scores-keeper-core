<?php

use Illuminate\Http\Request;
use App\Game;

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

Route::get('/games/live', function (Request $request) {
    /** @var \App\User $user_obj */
    $user_obj = \App\User::find( $request->user()->id );

    /** @var \App\Game $game_obj */
    $game_obj = $user_obj->get_live_game();
    if ( empty( $game_obj ) ) {
        return response()->json( [
            'success' => false,
            'code' => 'no-live-games',
        ] );
    }

    return response()->json( $game_obj->get_game_data() );
})->middleware('auth:api');


Route::post('/games/{game_id}/points', function (Request $request, $id) {
    /** @var \App\Game $game_obj */
    $game_obj = \App\Game::find( (int) $id);

    if ( ! $game_obj->is_player_in_game( $request->user()->id ) ) {
        return response()->json( [
            'success' => false,
            'code' => 'not-allowed',
        ] );
    }

    return response()->json( $game_obj->add_point( (int) $request->input('player_id'), (int) $request->input('action_type') ) );

})->middleware('auth:api');
