<?php

namespace App\Http\Controllers;

use App\Game;
use \App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $players = $user_obj->get_friends('active');
        if ( empty( $players ) ) {
            return false;
        }

        $current_user = new \stdClass;
        $current_user->id = $user_obj->id;
        $current_user->name = $user_obj->name;

        $players->prepend( $current_user);

        return view('games.create', compact('players'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = request()->validate([
            'player1'       => 'required',
            'player2'       => 'required',
            'player3'       => 'required',
            'player4'       => 'required',
            'points_to_win' => 'required',
        ]);

        // Check if all players are unique
        $all_players = array_unique( [ $attributes['player1'], $attributes['player2'], $attributes['player3'], $attributes['player4'] ] );
        if ( 4 !== count( $all_players ) ) {
            // TODO: use flash error messages
            die('Cheating with players!');
        }

        // TODO: Check if the referee is not from the players
        $referee = request('referee');
        if ( 0 < (int) $referee ) {
            $attributes['referee'] = $referee;
        }

        // TODO: Check that all players are active friends

        // TODO: Check that nobody is already in a live game

        // Starts now?
        if ( 'on' === request('start_now') ) {
            $attributes['status'] = 'live';
            $attributes['start_date'] = time() * 1000;
        } else {
            $attributes['status'] = 'pending';
        }

        $attributes['enable_turns'] = 'on' === request('enable_turns') ? true : false;

        Game::create($attributes);

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
        $players = $game->get_players();

        return view('games.show',compact('game','players'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
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

        // Get the currently authenticated user
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

        return view('games.live')->withToken($access_token);
      }
}
