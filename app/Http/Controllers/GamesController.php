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

        $players = $user_obj->friends('active');
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
        $player_attributes = request()->validate([
            'player1'       => 'required',
            'player2'       => 'required',
            'player3'       => 'required',
            'player4'       => 'required',
        ]);

        // Check if all players are unique
        $all_players = array_unique( [ $player_attributes['player1'], $player_attributes['player2'], $player_attributes['player3'], $player_attributes['player4'] ] );

        if ( 4 !== count( $all_players ) ) {
            // TODO: use flash error messages
           // die('Cheating with players!');
        }

        $attributes = request()->validate([
            'points_to_win' => 'required',
        ]);

        // TODO: Check if the referee is not from the players
        $referee = request('referee');
        if ( 0 < (int) $referee ) {
            $attributes['referee'] = $referee;

            foreach ($all_players as $player) {
                if ((int) $player === (int) $referee) {
                    die('Referee cannot be a player');
                }
            }
        }

        // TODO: Check that all players are active friends

        // TODO: Check that nobody is already in a live game
        foreach ($all_players as $player) {
            $user_obj = User::find($player);
            $games = $user_obj->games;
            foreach ($games as $game) {
                if ($game->is_game_live()) {
                    die($user_obj->name . ' is in a live game already');
                }
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
        $players = $game->players;

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
        // Remove game history
        foreach( $game->history()->get() as $game_point ) {
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
