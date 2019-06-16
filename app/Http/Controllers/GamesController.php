<?php

namespace App\Http\Controllers;

use App\Game;
use \App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::all();

        return view('games.index')->withGames($games);
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
        $game = new Game();

        $game->player1 = request('player1');
        $game->player2 = request('player2');
        $game->player3 = request('player3');
        $game->player4 = request('player4');

        $game->save();

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
        $player1 = User::where('id',$game->player1)->first();
        $player2 = User::where('id',$game->player2)->first();
        $player3 = User::where('id',$game->player3)->first();
        $player4 = User::where('id',$game->player4)->first();

        $players = [
            'Player 1' => $player1,
            'Player 2' => $player2,
            'Player 3' => $player3,
            'Player 4' => $player4
        ];

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
        $game->player1 = (int) $request->player1;
        $game->player2 = (int) $request->player2;
        $game->player3 = (int) $request->player3;
        $game->player4 = (int) $request->player4;

        $game->save();

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
        //
    }

    /**
     * Return live game of currently authenticated user.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    function live()
    {
          // Get the currently authenticated user's ID...
          $id = Auth::id();

          if ( (int) $id <= 0 ) {
              return redirect(url('/') );

          }

          $game_live = Game::where( [
              [ 'status', 'live' ],
          ])->first();

          if ( empty( $game_live ) ) {
              return redirect(url('/') );
          }

          return view('games.live');
      }
}
