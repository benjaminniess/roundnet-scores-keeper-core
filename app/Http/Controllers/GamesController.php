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
        $user_id = auth()->id();

        $games = Game::where('player1', '=', $user_id )
            ->orWhere('player2', '=', $user_id)
            ->orWhere('player3', '=', $user_id)
            ->orWhere('player4', '=', $user_id)
            ->orderBy('start_date', 'desc')->get();

        if(empty($games)){
            return redirect(url('/') );
        }

        foreach ($games as $game) {
            $game->players = $game->get_players();
        }

        return view('games.index',compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $players = User::all();
        return view('games.create',compact('players'));
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

        $attributes['status'] = 'on' === request('start_now') ? 'live' : 'pending';
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
