<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use Carbon\Carbon;
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
        $user_obj = User::find(Auth::id());

        $games = $user_obj->get_games_including_referee()->orderBy('id', 'desc')->paginate(10);

        // For each game, get logged user team
        foreach ($games as $game) {
            $game->formated_end_date = $game->set_end_date();
            $game->is_referee = $game->is_referee();   
            $game->winning_game = $game->set_winning_game($user_obj->id);
        }

        return view('games.index', compact('games'));
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

        $players = $user_obj
            ->friends('active')
            ->merge($user_obj->friends('guest'));
        if (empty($players)) {
            return redirect(url('/'));
        }

        $current_user = new \stdClass();
        $current_user->id = $user_obj->id;
        $current_user->name = $user_obj->name;

        $players->prepend($current_user);

        return view('games.create', compact('players'));
    }

    /**
     * When a user clicks to the "start" button on a pending game
     *
     * @param Request $request
     * @param $game
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function start(Request $request, $game_id)
    {
        if (0 >= (int) $game_id) {
            abort(403, 'No game ID');
        }

        /** @var User $user_obj */
        $user_obj = \App\User::find(Auth::id());
        if (empty($user_obj)) {
            abort(403, 'You must be logged.');
        }

        if ($user_obj->is_in_a_live_game()) {
            abort(403, 'You are already in a live game.');
        }

        /** @var \App\Game $game_obj */
        $game_obj = \App\Game::find((int) $game_id);
        if (empty($game_obj) || !$game_obj->is_player_in_game($user_obj->id)) {
            abort(403, 'Your are not in this game');
        }

        if ('pending' !== $game_obj->status) {
            abort(403, 'This game is not ready.');
        }

        $game_obj->status = 'live';
        $game_obj->save();

        return redirect(url('/games/live'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        /** @var User $user_obj */
        $user_obj = \App\User::find(Auth::id());

        $validator = Validator::make($request->all(), []);

        $guests_to_create = [];
        $player_attributes = [];

        // Loop through players
        for ($i = 1; $i < 5; $i++) {
            $guest_field = request('guest' . $i);
            $player_field = request('player' . $i);

            // One of the 2 fields must be set
            if (empty($guest_field) && 0 >= (int) $player_field) {
                $validator
                    ->errors()
                    ->add(
                        'player' . $i,
                        'You must select a player or a guest for player 1'
                    );
                return redirect('games/create')
                    ->withErrors($validator)
                    ->withInput();
            }

            // Guest?
            if (!empty($guest_field)) {
                if (\App\Helpers::nickname_exists($guest_field)) {
                    $validator
                        ->errors()
                        ->add('guest' . $i, 'This nickname already exists');
                    return redirect('games/create')
                        ->withErrors($validator)
                        ->withInput();
                }

                $guests_to_create[$i] = $guest_field;
            } else {
                $player_attributes['player' . $i] = request('player' . $i);
            }
        }

        if ($validator->fails()) {
            return redirect('games/create')
                ->withErrors($validator)
                ->withInput();
        }

        // Check if all players are unique
        $all_players = array_unique($player_attributes);

        if (4 !== count($all_players) + count($guests_to_create)) {
            $validator
                ->errors()
                ->add('4players', 'You must select 4 different players.');
            return redirect('games/create')
                ->withErrors($validator)
                ->withInput();
        }

        $attributes = request()->validate([
            'points_to_win' => 'required'
        ]);

        // Store referee id if defined
        $referee = request('referee');
        if (0 < (int) $referee) {
            $attributes['referee'] = $referee;

            /** @var \App\User $referee_obj */
            $referee_obj = User::find($referee);
            if ( $referee_obj->is_in_a_live_game() ) {
                $validator
                    ->errors()
                    ->add(
                        'referee',
                        'The referee is already in a live game.'
                    );
            }

            // Check if referee is not among players
            foreach ($all_players as $player) {
                if ((int) $player === (int) $referee) {
                    $validator
                        ->errors()
                        ->add(
                            'referee',
                            'The referee cannot be in the game players.'
                        );
                    return redirect('games/create')
                        ->withErrors($validator)
                        ->withInput();
                }
            }
        }

        // Check if all players are friends
        foreach ($all_players as $player) {
            /** @var User $player_obj */
            $player_obj = User::find($player);
            if ((int) $user_obj->id !== (int) $player_obj->id) {
                if (!$user_obj->is_friend($player_obj->id)) {
                    abort(403, 'Cheating with friends.');
                }
            }

            // Check that nobody is already in a live game
            if (
                'start_now' === request('start_game_options') &&
                $player_obj->is_in_a_live_game()
            ) {
                $validator
                    ->errors()
                    ->add(
                        'player-in-game',
                        $player_obj->name . ' is already in a live game'
                    );
                return redirect('games/create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        // Check is scores have been set when add scores is checked
            if ('add_score' === request('start_game_options') && (null === request('score_team_a') || null === request('score_team_b'))) {
                $validator
                    ->errors()
                    ->add(
                        'set_scores',
                        'You must fill the score for both teams'
                    );
                return redirect('games/create')
                    ->withErrors($validator)
                    ->withInput();
            }

        // Set game status depending on start game options
        if ('start_now' === request('start_game_options')) {
            $attributes['status'] = 'live';
            $attributes['start_date'] = time() * 1000;
        }
        if ('for_later' === request('start_game_options')) {
            $attributes['status'] = 'pending';
        }
        if ('add_score' === request('start_game_options')) {
            $attributes['start_date'] = time() * 1000;
            $attributes['score_team_1'] = request('score_team_a');
            $attributes['score_team_2'] = request('score_team_b');
        }

        $attributes['enable_turns'] =
            'on' === request('enable_turns') ? true : false;

        // Once we've checked that we have no doublons, we can create guests users
        foreach ($guests_to_create as $player_number => $nickname) {
            $guest_id = \App\Helpers::create_guest_account(
                $nickname,
                $user_obj->id
            );
            if (0 >= (int) $guest_id) {
                $validator
                    ->errors()
                    ->add('guest' . $i, 'Error while creating guest');
                return redirect('games/create')
                    ->withErrors($validator)
                    ->withInput();
            }

            $player_attributes['player' . $player_number] = $guest_id;
        }

        // First to serve
        $first_to_serve = request('first_to_serve');
        if ('rand' === $first_to_serve) {
            $first_to_serve = rand(1, 4);
        }

        $first_to_serve = (int) $first_to_serve;
        $attributes['current_server'] =
            $player_attributes['player' . $first_to_serve];

        $game = Game::create($attributes);

        $game
            ->players()
            ->attach(\App\User::find($player_attributes['player1']), [
                'position' => 1
            ]);
        $game
            ->players()
            ->attach(\App\User::find($player_attributes['player2']), [
                'position' => 2
            ]);
        $game
            ->players()
            ->attach(\App\User::find($player_attributes['player3']), [
                'position' => 3
            ]);
        $game
            ->players()
            ->attach(\App\User::find($player_attributes['player4']), [
                'position' => 4
            ]);

        $confirmation_message = ('add_score' === request('start_game_options')) ? 'The game has been created and the score set.' : 'The game has been created. You can start it whenever you like.' ;
        if ('start_now' === request('start_game_options')) {
            return redirect('/games/live');
        }
        if ('add_score' === request('start_game_options')) {
            $game->close_game();
        }

        return redirect('/games')->with(
            'message',
            $confirmation_message
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        // Check is the user is authorized to view the game
        $this->authorize('view', $game);
        $user_obj = User::find(auth()->id());

        if ($game->is_game_live()) {
            return redirect('/games/live');
        }

        $ordered_players = $game->get_players_position();
        foreach ($ordered_players as $position => $player_id) {
            $ordered_players[$position] = \App\User::find($player_id);
        }

        foreach ($ordered_players as $player) {
            $player->actions_types_chart_data = $player->get_chart_js_actions_types( $game->id );
        }

        if ($game->has_points()){
            // Get player object foreach point
            foreach ($game->points as $point) {
                $point->player = $point->get_point_owner();
            }

            // Get action type object foreach point
            foreach ($game->points as $point) {
                $point->action_type = $point->get_point_action_type();
            }
        }

        // Get game's referee
        if ( isset($game->referee) ) {
            $game->referee = $game->referee();
        }else{
            $game->referee = NULL;
        }

        return view('games.show')->with([
            'game' => $game,
            'players' => $ordered_players,
            'history_chart' => $game->get_chart_js_game_history(),
            'individual_charts' => $game->get_chart_js_players_scores()
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

        return view('games.edit', compact('game', 'players'));
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

        return redirect()->route('games.show', $game);
    }

    /**
     * Update the score of the specified game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function set_score(Request $request, Game $game)
    {
        // Check is the user is authorized to update the game
        $this->authorize('update', $game);

        $user_obj = User::find(auth()->id());
        $attributes = request()->validate([
            'score_team_1' => 'required',
            'score_team_2' => 'required'
        ]);
        $attributes['start_date'] = time() * 1000;

        $game->update($attributes);
        $game->close_game();

        return redirect()->route('games.index')->with(
            'message',
            'The score has been set'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     */
    public function destroy(Game $game)
    {
        // Check is the user is authorized to delete the game
        $this->authorize('delete', $game);

        $user_obj = User::find( auth()->id() );
        $game->destroy_game( $user_obj->id );
        return redirect()
            ->back()
            ->with('message', 'The game has been deleted.');
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

        $live_game = $user_obj->get_live_game();
        if (empty($live_game)) {
            return redirect(url('/'));
        }

        $access_token = $user_obj->get_access_token();
        if (empty($access_token)) {
            return redirect(url('/'));
        }

        return view('games.live');
    }
}
