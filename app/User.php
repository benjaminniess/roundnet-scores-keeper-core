<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\UsersBadges;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    /**
     * Checks if the current user is an admin
     *
     * @return bool
     */
    public function is_admin()
    {
        return 'admin' === $this->type;
    }

    /**
     * Checks in cookies and returns a valid token for the current user. If no access token, it will generate a new one
     *
     * @return mixed
     */
    public function get_access_token($new = false)
    {
        if (
            false === $new &&
            isset($_COOKIE['user_access_token']) &&
            !empty($_COOKIE['user_access_token'])
        ) {
            return $_COOKIE['user_access_token'];
        }

        DB::table('oauth_access_tokens')
            ->where(
                [
                    ['user_id', '=', $this->getAttribute('id')],
                    ['name', '=', 'ReactToken']
                ],
                '=',
                $this->getAttribute('id')
            )
            ->delete();
        $access_token = Auth::user()->createToken('ReactToken')->accessToken;
        setcookie(
            'user_access_token',
            $access_token,
            time() + 30 * 3600 * 24,
            '/'
        );

        return $access_token;
    }

    /**
     * Get all user games
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function games()
    {
        return $this->belongsToMany(
            'App\Game',
            'players',
            'user_id',
            'game_id'
        );
    }

    /**
     * Get all user badges
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function badges()
    {
        return $this->belongsToMany(
            'App\Badge',
            'users_badges',
            'user_id',
            'badge_id'
        );
    }

	/**
	 * Get all games of the current user including the referee games
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
    public function get_games_including_referee( $status = null ) {
	    $query = Game::select('games.*')
        ->join('players', 'games.id', '=', 'players.game_id')
        ->where( function ( $query ) {
            $query
            ->where('players.user_id', '=', $this->id)
            ->orWhere('games.referee', '=', $this->id);
        });
        if ( !is_null($status) ) {
            $query
            ->where( 'games.status', '=', $status );
        }
        $query->groupBy('games.id');
        return $query;
    }

    /**
     * Get a collection of user players
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function players()
    {
        return $this->hasMany('\App\Player', 'user_id');
    }

    /**
     * Get a collection of badges
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function users_badges () {
        return $this->belongsToMany('Badge', 'users_badges', 'user_id', 'badge_id')->withTimestamps();
    }

    /**
     * Return a collection of games where the user is the game referee
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function games_as_referee()
    {
        return $this->hasMany('App\Game', 'referee');
    }

    /**
     * Checks if the given user is in a live game and returns it
     *
     * @return \App\Game
     */
    public function get_live_game()
    {
        return $this->games()
            ->where('status', '=', 'live')
	        ->orWhere( function ($query) {
		        $query->where( 'referee', '=', $this->id)
		              ->where('status', '=', 'live');
	        } )
            ->first();
    }

    /**
     * Checks if the user is already in a live game
     *
     * @return bool
     */
    public function is_in_a_live_game()
    {
        return !empty($this->get_live_game());
    }

    /**
     * Prepare user data for rest API usage
     *
     * @return array
     */
    public function get_user_data()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => 'comming-soon'
        ];
    }

    /**
     * Get all user friends
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function friends($status = '')
    {
        $query_a_to_b = $this->belongsToMany(
            'App\User',
            'user_relationships',
            'user_id_1',
            'user_id_2'
        );
        $query_b_to_a = $this->belongsToMany(
            'App\User',
            'user_relationships',
            'user_id_2',
            'user_id_1'
        );

        if (!empty($status)) {
            $query_a_to_b->where('status', '=', $status);
            $query_b_to_a->where('status', '=', $status);
        }

        return $query_a_to_b->get()->merge($query_b_to_a->get());
    }

    /**
     * Get incoming friend requests
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get_friend_requests()
    {
        return $this->belongsToMany(
            'App\User',
            'user_relationships',
            'user_id_2',
            'user_id_1'
        )
            ->where('status', '=', 'pending')
            ->get();
    }

    /**
     * Get all user relationships
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get_all_relationships()
    {
        return UserRelationships::select()
        ->where(function ($query){
            $query
            ->where('user_id_1', '=', $this->id)
            ->orWhere('user_id_2', '=', $this->id);
        })
        ->get();
    }

    /**
     * Get one user relationship info
     *
     *@return object
     */
    public function get_relationship($friend_id)
    {
        $relationship = UserRelationships::select()
            ->where(function ($query) use ($friend_id) {
                $query
                    ->where('user_relationships.user_id_1', '=', $this->id)
                    ->orWhere('user_relationships.user_id_1', '=', $friend_id);
            })
            ->where(function ($query) use ($friend_id) {
                $query
                    ->where('user_relationships.user_id_2', '=', $this->id)
                    ->orWhere('user_relationships.user_id_2', '=', $friend_id);
            });

        return $relationship->first();
    }

    /**
     * Check relationship between 2 users
     *
     *@return boolean
     */
    public function is_friend($friend_id)
    {
        if (empty($this->get_relationship($friend_id))) {
            return false;
        }
        return true;
    }

    /**
     * Get the total time spent playing by a user
     *
     * @return string
     */
    public function time_spent_playing() {
        $games_as_player = $this->games;
        $player_games_duration = [];
        foreach ($games_as_player as $game) {
            $game->duration = $game->duration();
            array_push($player_games_duration,$game->duration());
        }
        return gmdate('H:i:s', array_sum($player_games_duration));
    }

    /**
     * Get the total time spent refereing by a user
     *
     * @return string
     */
    public function time_spent_refereing() {
        $games_as_referee = $this->games_as_referee()->get();
        $referee_games_duration = [];
        foreach ($games_as_referee as $game) {
            $game->duration = $game->duration();
            array_push($referee_games_duration,$game->duration());
        }
        return gmdate('H:i:s', array_sum($referee_games_duration));
    }

    /**
     * Get number of user total played games
     *
     * @return int
     */
    public function total_games( $status ) {
        $games = $this->games()->where('games.status', '=', $status)->get();
        return count($games);
    }

    /**
     * Return all user points
     *
     *@return int
     */
    public function points( $game_id = NULL )
    {
        $query = $this->hasMany('\App\Game_Point', 'player_id');
        if ($game_id !== NULL) {
            $query->where('game_points.game_id', '=', $game_id);
        }
        return $query;
    }

    /**
     * Return all user actions types grouped by actions types for chart JS
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function actions_types( $game_id = NULL )
    {
        $query = Action_Type::selectRaw('actions_types.*, count(actions_types.id) AS total_action')
        ->join('game_points', 'game_points.action_type_id', '=', 'actions_types.id')
        ->where('game_points.player_id', '=', $this->id);
        if ( $game_id !== NULL ) {
            $query = $query->where('game_id', '=', $game_id);
        }
        $query = $query
        ->groupBy('actions_types.id')
        ->get();

        return $query;
    }

    /**
     * Return all user positive points
     *
     *@return int
     */
    public function points_by_type( $type )
    {
        $query = $this->hasMany('\App\Game_Point', 'player_id')
        ->join('actions_types', 'game_points.action_type_id', '=', 'actions_types.id')
        ->where('actions_types.action_type', '=', $type);

        return $query;
    }

    /**
     * Return user winning games
     *
     *@return App\Game
     */
    public function winning_games()
    {
        $games = Game::join('players', 'players.game_id', '=', 'games.id')
        ->join('users', 'players.user_id', '=', 'users.id')
        ->where('users.id', '=', $this->id)
        ->where(function ($query) {
            $query
            ->whereColumn('games.score_team_1', '>', 'games.score_team_2');
                $query
                ->where(function ($query){
                    $query
                    ->where('players.position', '=', 1)
                    ->orWhere('players.position', '=', 2);
                });
            })
        ->orWhere(function ($query){
            $query
            ->whereColumn('games.score_team_2', '>', 'games.score_team_1');
                $query
                ->where(function ($query){
                    $query
                    ->where('players.position', '=', 3)
                    ->orWhere('players.position', '=', 4);

                });
            })
            ->groupBy('games.id');

        return $games->get();
    }

    /**
     * Generates the json for chart js
     *
     * @return false|string
     */
    public function get_chart_js_victory_stats() {

        // Get user total winning games
        $total_winning_games = $this->winning_games()->count();

        // Get user total games
        $total_games = $this->games->count();

        // Calculate % of victory
        // $percentage_victory = round(( $total_winning_games / $total_games ) * 100, 1 );

        // Calculate user total losing games
        $total_losing_games = $total_games - $total_winning_games;

        $labels = ['Lost games', 'Won games'];
        $background_color = ['#c45850', '#3cba9f'];
        $victory_stats = [$total_losing_games, $total_winning_games];

        $victory_stats_chart = json_encode([
                'labels'   => $labels,
                'datasets' => [
                    [
                        'backgroundColor' => $background_color,
                        'data'            => $victory_stats
                    ]
                ],
            ]);

        return $victory_stats_chart;
    }

    /**
     * Generates the json for chart js
     *
     * @return false|string
     */
    public function get_chart_js_points_types() {

        // Get logged user points by type and count each
        $positive_points = $this->points_by_type(Action_Type::POSITIVE_POINTS)->count();
        $negative_points = $this->points_by_type(Action_Type::NEGATIVE_POINTS)->count();
        $neutral_points = $this->points_by_type(Action_Type::NEUTRAL_POINTS)->count();

        $labels = [
            'Positive points',
            'Negative points',
            'Neutral points'
        ];
        $points_types = [
            $positive_points,
            $negative_points,
            $neutral_points
        ];
        $background_color = [
            '#3cba9f',
            '#c45850',
            '#e8c3b9'

        ];

        $points_types_chart = json_encode([
                'labels'   => $labels,
                'datasets' => [
                    [
                        'backgroundColor' => $background_color,
                        'data'            => $points_types
                    ]
                ],
            ]);

        return $points_types_chart;
    }


    /**
     * Return all user actions types grouped by actions types for chart JS
     *
     * @return false|string
     */
    public function get_chart_js_actions_types( $game_id = NULL )
    {
        $actions_types = $this->actions_types( $game_id );
        $data = [];
        $labels = [];
        $colors = [];
        foreach ($actions_types as $action_type) {
            if ($action_type->action_type === 'positive') {
                array_push($colors, '#3cba9f');
            }
            if ($action_type->action_type === 'negative') {
                array_push($colors, '#c45850');
            }
            if ($action_type->action_type === 'neutral') {
                array_push($colors, '#e8c3b9');
            }

            array_push($data,$action_type->total_action);
            array_push($labels,$action_type->name);
        }
        // dump($data,$labels);
        return json_encode([
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors
                ]
            ],
            'labels' => $labels
        ]);
    }

    /**
     * Return user winning games
     *
     *@return string | false
     */
    public function get_team( $game_id )
    {
        $position = Player::select('position')
        ->join('games', 'games.id', '=', 'players.game_id')
        ->where('games.id', '=', $game_id)
        ->where('players.user_id', '=', $this->id)
        ->first();

        if ( empty($position) ) {
        	return false;
        }
        // Get user team with user position in game
        if( (int) $position->position === 1 || (int) $position->position === 2 ){
            return Game::TEAM_1;
        } else {
            return Game::TEAM_2;
        }
    }

    /**
     * Destroy a user from database
     *
     * @return
     */
    public function destroy_user()
    {
        // Delete all user games
        if(!$this->games->isEmpty()){
            foreach ($this->games as $game) {
                $game->destroy_game( $this->id );
            }
        }

        // Delete all user_relationships entries
        if (!$this->get_all_relationships()->isEmpty()) {
            foreach ($this->get_all_relationships() as $relationship) {
                $relationship->delete();
            }
        }

        // Delete the user himself
        $this->delete();
    }
}