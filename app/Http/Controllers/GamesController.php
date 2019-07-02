<?php

namespace App\Http\Controllers;

use App\Game;
use \App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_obj = \App\User::find(Auth::id());
        if ( empty( $user_obj ) ) {
            return redirect(url('/') );
        }

        $games = $user_obj->games;

        return view('games.index',compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /** @var User $user_obj */
        $user_obj = \App\User::find(Auth::id());
        if ( empty( $user_obj ) ) {
            return redirect(url('/') );
        }

        $players = $user_obj->friends('active');
        if ( empty( $players ) ) {
            return redirect(url('/') );
        }

        $current_user = new \stdClass;
        $current_user->id = $user_obj->id;
        $current_user->name = $user_obj->name;

        $players->prepend( $current_user);

        return view('games.create', compact('players'));
    }

    /**
     * When a user clicks to the "start" button on a pending game
     *
     * @param Request $request
     * @param $game
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function start(Request $request, $game_id ) {
        if ( 0 >= (int) $game_id ) {
            return redirect(url('/') );
        }

        /** @var User $user_obj */
        $user_obj = \App\User::find(Auth::id());
        if ( empty( $user_obj ) ) {
            abort(403, 'You must be logged.');
        }

        if ( $user_obj->is_in_a_live_game() ) {
            abort(403, 'You are already in a live game.');
        }

        /** @var \App\Game $game_obj */
        $game_obj = \App\Game::find( (int) $game_id );
        if ( empty( $game_obj ) || ! $game_obj->is_player_in_game( $user_obj->id ) ) {
            abort(403, 'Your are not in this game');
        }

        if ( 'pending' !== $game_obj->status ) {
            abort(403, 'This game is not ready.');
        }

        $game_obj->status = 'live';
        $game_obj->save();

        return redirect(url('/games/live') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_obj = \App\User::find(Auth::id());
        if ( empty( $user_obj ) ) {
            return redirect(url('/') );
        }

	    $validator = Validator::make($request->all(),[
		    'player1'       => 'required',
		    'player2'       => 'required',
		    'player3'       => 'required',
		    'player4'       => 'required',
	    ]);

        if ( $validator->fails() ) {
            return redirect('games/create')
                ->withErrors($validator)
                ->withInput();
        }

        $player_attributes = request()->validate([
            'player1'       => 'required',
            'player2'       => 'required',
            'player3'       => 'required',
            'player4'       => 'required',
        ]);

        // Check if all players are unique
        $all_players = array_unique( [ $player_attributes['player1'], $player_attributes['player2'], $player_attributes['player3'], $player_attributes['player4'] ] );

        if ( 4 !== count( $all_players ) ) {
            $validator->errors()->add('4players', 'You must select 4 different players.');
            return redirect('games/create')
                ->withErrors($validator)
                ->withInput();
        }

        $attributes = request()->validate([
            'points_to_win' => 'required',
        ]);

        $referee = request('referee');
        if ( 0 < (int) $referee ) {
            $attributes['referee'] = $referee;

            foreach ($all_players as $player) {
                if ((int) $player === (int) $referee) {
                    $validator->errors()->add('referee', 'The referee cannot be in the game players.');
                    return redirect('games/create')
                        ->withErrors($validator)
                        ->withInput();
                }
            }
        }

        foreach ($all_players as $player) {
            /** @var User $player_obj */
            $player_obj = User::find($player);
            if ((int) $user_obj->id !== (int) $player_obj->id) {
                if (!$user_obj->is_friend($player_obj->id)) {
                    abort(403, 'Cheating with friends.');
                }
            }

            // Check that nobody is already in a live game
            if( 'on' === request('start_now' ) && $player_obj->is_in_a_live_game() ) {
                $validator->errors()->add('player-in-game', $player_obj->name . ' is already in a live game');
                return redirect('games/create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        // Starts now?
        if ( 'on' === request('start_now') ) {
            $attributes['status'] = 'live';
            $attributes['start_date'] = time() * 1000;
        } else {
            $attributes['status'] = 'pending';
        }

        $attributes['enable_turns'] = 'on' === request('enable_turns') ? true : false;

        $game = Game::create($attributes);

        $game->players()->attach( \App\User::find($player_attributes['player1'] ), [ 'position' => 1 ]);
        $game->players()->attach( \App\User::find($player_attributes['player2'] ), [ 'position' => 2 ]);
        $game->players()->attach( \App\User::find($player_attributes['player3'] ), [ 'position' => 3 ]);
        $game->players()->attach( \App\User::find($player_attributes['player4'] ), [ 'position' => 4 ]);

        if ( 'on' === request('start_now') ) {
            return redirect('/games/live');
        }

        return redirect('/games');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        $user_obj = User::find(auth()->id());
        if ( empty( $user_obj ) ) {
            return redirect(url('/') );
        }

        if ( $game->is_game_live() ) {
	        return redirect('/games/live');
        }
        $ordered_players = $game->get_players_position();
        foreach ( $ordered_players as $position => $player_id ) {
            $ordered_players[ $position ] = \App\User::find($player_id);
        }

        return view('games.show')->with([
            'game' => $game,
            'players' => $ordered_players,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
	    abort(403, 'For admin only');

        $players = User::all();

        return view('games.edit', compact('game','players'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $attributes = request()->validate([
            'player1' => 'required',
            'player2' => 'required',
            'player3' => 'required',
            'player4' => 'required'
        ]);

        $game->update($attributes);

        return redirect()->route('games.show',$game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        // Remove game history
        foreach( $game->points()->get() as $game_point ) {
            $game_point->delete();
        }

        // Remove game players
        $players = $game->hasMany('\App\Player', 'game_id' )->get();
        foreach ( $players as $player ) {
            $player->delete();
        }

        // Remove game itself
        $game->delete();

        return redirect('/games');
    }

    /**
     * Return live game of currently authenticated user.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function live()
    {
        /** @var User $user_obj */
        $user_obj = \App\User::find(Auth::id());
        if ( empty( $user_obj ) ) {
          return redirect(url('/') );
        }

        $live_game = $user_obj->get_live_game();
        if ( empty( $live_game ) ) {
            return redirect(url('/') );
        }

        $access_token = $user_obj->get_access_token();
        if ( empty( $access_token ) ) {
            return redirect(url('/') );
        }

        return view('games.live');
      }
}
