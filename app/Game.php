<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Game extends Model
{
    protected $guarded = ['id'];

    const TEAM_1 = 'team 1';
    const TEAM_2 = 'team 2';

    /**
     * Get all points associated to the given game
     *
     */
    public function points()
    {
        return $this->hasMany('\App\Game_Point', 'game_id');
    }

    /**
     * Get amount of points in a game
     *
     */
    public function count_points()
    {
        return $this->hasMany('\App\Game_Point', 'game_id')->count();
    }

    /**
     * Generates the json for chart js
     *
     * @return false|string
     */
    public function get_chart_js_game_history()
    {
        $points = $this->points();

        $scores = $this->get_scores();
        $color_team_1 =
            $scores['team1'] < $scores['team2'] ? '#e3342f' : '#38c172';
        $color_team_2 =
            $scores['team1'] > $scores['team2'] ? '#e3342f' : '#38c172';
        $team_a_scores = [
            [
                'x' => 0,
                'y' => 0
            ]
        ];
        $team_b_scores = [
            [
                'x' => 0,
                'y' => 0
            ]
        ];
        foreach ($points->get() as $key => $point) {
            /** @var Game_Point $point */
            $team_a_scores[] = [
                'x' => $key + 1,
                'y' => (int) $point->score_team_1
            ];

            $team_b_scores[] = [
                'x' => $key + 1,
                'y' => (int) $point->score_team_2
            ];
        }

        $labels = [];
        for ($i = 0; $i <= $points->count(); $i++) {
            $labels[] = $i;
        }

        return json_encode([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Team A',
                    'borderColor' => $color_team_1,
                    'backgroundColor' => '#FFF',
                    'fill' => false,
                    'data' => $team_a_scores
                ],
                [
                    'label' => 'Team B',
                    'borderColor' => $color_team_2,
                    'backgroundColor' => '#FFF',
                    'fill' => false,
                    'data' => $team_b_scores
                ]
            ]
        ]);
    }

    /**
     * The chart JS individual players scores json
     *
     * @return false|string
     */
    public function get_chart_js_players_scores()
    {
        $players = $this->get_players_position();

        $player_1 = \App\User::find($players[1]);
        $player_2 = \App\User::find($players[2]);
        $player_3 = \App\User::find($players[3]);
        $player_4 = \App\User::find($players[4]);

        $individual_scores = [];
        foreach ($players as $player_id) {
            $individual_scores[$player_id] = 0;
        }

        foreach ($this->points()->get() as $point) {
            /** @var Game_Point $point */
            $type = $point->get_point_action_type();

            if ('positive' === $type->action_type) {
                $individual_scores[(int) $point->player_id]++;
            } elseif ('negative' === $type->action_type) {
                $individual_scores[(int) $point->player_id]--;
            }
        }

        return json_encode([
            'labels' => [
                $player_1->name,
                $player_2->name,
                $player_3->name,
                $player_4->name
            ],
            'datasets' => [
                [
                    'label' => 'Individual scores',
                    'borderColor' => '#000',
                    'backgroundColor' => [
                        $this->get_winning_team() === self::TEAM_1
                            ? '#38c172'
                            : '#e3342f',
                        $this->get_winning_team() === self::TEAM_1
                            ? '#38c172'
                            : '#e3342f',
                        $this->get_winning_team() === self::TEAM_2
                            ? '#38c172'
                            : '#e3342f',
                        $this->get_winning_team() === self::TEAM_2
                            ? '#38c172'
                            : '#e3342f'
                    ],
                    'fill' => false,
                    'data' => [
                        [
                            'x' => $player_1->name,
                            'y' => $individual_scores[$player_1->id]
                        ],
                        [
                            'x' => $player_2->name,
                            'y' => $individual_scores[$player_2->id]
                        ],
                        [
                            'x' => $player_3->name,
                            'y' => $individual_scores[$player_3->id]
                        ],
                        [
                            'x' => $player_4->name,
                            'y' => $individual_scores[$player_4->id]
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get game rallies average duration
     *
     */
    public function points_average_duration()
    {
        $points = $this->points;

        $duration_array = [];

        foreach ($points as $point) {
            $previous_point_obj = Game_Point::where(
                'created_at',
                '<',
                $point->created_at
            )
                ->orderBy('created_at', 'desc')
                ->first();

            $current_point = $point->created_at;

            if (!empty($previous_point_obj)) {
                $previous_point = $previous_point_obj->created_at;
            } else {
                $previous_point = $this->created_at;
            }

            $duration_in_seconds = $current_point->diffInSeconds(
                $previous_point
            );
            array_push($duration_array, $duration_in_seconds);
        }

        $duration_average = array_sum($duration_array) / count($duration_array);

        return round($duration_average, 1) . 's';
    }

    /**
     * Get game duration in seconds
     *
     */
    public function duration()
    {
        $start_date = (int) $this->start_date;
        $end_date = (int) $this->end_date;

        $duration = ($end_date - $start_date) / 1000;

        return $duration;
    }
    /**
     * Destroy a game from database
     *
     * @return
     */
    public function destroy_game( $auth_user_id )
    {
        if (!$this->is_player_in_game( $auth_user_id )) {
            abort(403, 'Cheating?');
        }

        // Remove game history
        foreach ($this->points()->get() as $game_point) {
            $game_point->delete();
        }

        // Remove game players
        $players = $this->hasMany('\App\Player', 'game_id')->get();
        foreach ($players as $player) {
            $player->delete();
        }

        // Remove game itself
        $this->delete();
    }

    /**
     * Return the game start date if exists
     *
     * @return bool|false|string
     */
    public function get_date()
    {
        $start_date = $this->start_date;
        if (0 >= (int) $start_date) {
            return false;
        }

        return date('Y-m-d H:i', $start_date / 1000);
    }

    /**
     * Verify that a given player is in the current game
     *
     * @param $player_id
     * @return bool
     */
    public function is_player_in_game($player_id)
    {
        $players = $this->players()->get();
        foreach ($players as $player) {
            if ((int) $player->id === (int) $player_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the game status is live
     *
     * @return boolean
     */
    public function is_game_live()
    {
        return $this->status === 'live' ? true : false;
    }

    /**
     * Get the current game scores
     *
     * @return array
     */
    public function get_scores()
    {
        return [
            'team1' => $this->score_team_1,
            'team2' => $this->score_team_2
        ];
    }

    /**
     * Get the winning team
     *
     * @return string
     */
    public function get_winning_team()
    {
        if ($this->score_team_1 > $this->score_team_2) {
            return Game::TEAM_1;
        }
        if ($this->score_team_2 > $this->score_team_1) {
            return Game::TEAM_2;
        }
    }

    /**
     * Get the 4 players \App\User objects
     *
     */
    public function players()
    {
        return $this->belongsToMany(
            'App\User',
            'players',
            'game_id',
            'user_id'
        )->withPivot('position');
    }

    /**
     * Get a simple array with position => ID
     *
     * @return array
     */
    public function get_players_position()
    {
        $positions = [];
        foreach ($this->players()->get() as $player) {
            $positions[$player->pivot->position] = $player->id;
        }

        return $positions;
    }

    /**
     * Prepare all game data for API usage
     *
     * @return array
     */
    public function get_game_data()
    {
        $players = $this->players;

        $game_data = [
            'id' => (int) $this->id,
            'teams' => [
                'a' => [
                    'players' => [
                        'p1' => false,
                        'p2' => false
                    ]
                ],
                'b' => [
                    'players' => [
                        'p3' => false,
                        'p4' => false
                    ]
                ]
            ],
            'points' => $this->points,
            'score' => $this->get_scores(),
            'start_date' => (int) $this->start_date,
            'current_server' => (int) $this->current_server,
            'points_to_win' => (int) $this->points_to_win,
            'enable_turns' => (int) $this->enable_turns,
            'status' => $this->status
        ];

        foreach ($players as $player) {
            switch ($player->pivot->position) {
                case '1':
                    $game_data['teams']['a']['players'][
                        'p1'
                    ] = $player->get_user_data();
                    break;
                case '2':
                    $game_data['teams']['a']['players'][
                        'p2'
                    ] = $player->get_user_data();
                    break;
                case '3':
                    $game_data['teams']['b']['players'][
                        'p3'
                    ] = $player->get_user_data();
                    break;
                case '4':
                    $game_data['teams']['b']['players'][
                        'p4'
                    ] = $player->get_user_data();
                    break;
            }
        }

        return $game_data;
    }

    /**
     * Get the 3 families of action types
     *
     * @return array
     */
    public function get_actions_types()
    {
        return [
            'positive' => DB::table('actions_types')
                ->where('action_type', 'positive')
                ->get(),
            'neutral' => DB::table('actions_types')
                ->where('action_type', 'neutral')
                ->get(),
            'negative' => DB::table('actions_types')
                ->where('action_type', 'negative')
                ->get()
        ];
    }

    /**
     * Update game current score
     *
     * @param int $score_team_1
     * @param int $score_team_2
     */
    public function set_score(
        $score_team_1 = 0,
        $score_team_2 = 0,
        $next_server = false
    ) {
        $to_update = [
            'score_team_1' => $score_team_1,
            'score_team_2' => $score_team_2
        ];

        if (0 < (int) $next_server) {
            $to_update['current_server'] = $next_server;
            $this->current_server = $next_server;
        }

        if (
            !DB::table('games')
                ->where('id', $this->id)
                ->update($to_update)
        ) {
            return false;
        }

        $this->score_team_1 = $score_team_1;
        $this->score_team_2 = $score_team_2;

        return true;
    }

    /**
     * Change game status
     *
     * @param $status
     * @return bool
     */
    public function set_status($status)
    {
        if (
            !DB::table('games')
                ->where('id', $this->id)
                ->update([
                    'status' => 'closed'
                ])
        ) {
            return false;
        }

        $this->status = $status;

        return true;
    }

    /**
     * Ads a new point history to the game
     *
     * @param $player_id
     * @param $action_type_id
     * @return array
     */
    public function add_point($player_id, $action_type_id)
    {
        if (!$this->is_game_live()) {
            return [
                'success' => false,
                'code' => 'game-not-live'
            ];
        }

        if (!$this->is_player_in_game($player_id)) {
            return [
                'success' => false,
                'code' => 'not-in-game'
            ];
        }

        $action_type = DB::table('actions_types')
            ->where('id', '=', $action_type_id)
            ->first();
        if (empty($action_type)) {
            return [
                'success' => false,
                'code' => 'wrong-action-type'
            ];
        }

        $score_team_1 = $this->score_team_1;
        $score_team_2 = $this->score_team_2;

        $positions = $this->get_players_position();

        $current_server = (int) $this->current_server;
        $serve_order = [
            1 => $positions[1],
            2 => $positions[3],
            3 => $positions[2],
            4 => $positions[4]
        ];
        $current_server_position = array_search($current_server, $serve_order);
        $next_key =
            3 < $current_server_position ? 1 : $current_server_position + 1;
        $next_server = $serve_order[$next_key];

        // Player is in team 1 ?
        if (
            (int) $player_id === (int) $positions[1] ||
            (int) $player_id === (int) $positions[2]
        ) {
            if ('positive' === $action_type->action_type) {
                $score_team_1++;
                if (
                    $current_server_position === 2 ||
                    $current_server_position === 4
                ) {
                    $current_server = $next_server;
                }
            } elseif ('negative' === $action_type->action_type) {
                $score_team_2++;
                if (
                    $current_server_position === 1 ||
                    $current_server_position === 3
                ) {
                    $current_server = $next_server;
                }
            }
        } else {
            if ('positive' === $action_type->action_type) {
                $score_team_2++;
                if (
                    $current_server_position === 1 ||
                    $current_server_position === 3
                ) {
                    $current_server = $next_server;
                }
            } elseif ('negative' === $action_type->action_type) {
                $score_team_1++;
                if (
                    $current_server_position === 2 ||
                    $current_server_position === 4
                ) {
                    $current_server = $next_server;
                }
            }
        }

        $this->set_score($score_team_1, $score_team_2, $current_server);

        DB::table('game_points')->insert([
            'player_id' => $player_id,
            'action_type_id' => $action_type_id,
            'score_team_1' => $score_team_1,
            'score_team_2' => $score_team_2,
            'game_id' => $this->id,
            'created_at' => Carbon::now()
        ]);

        if (
            $score_team_1 >= $this->points_to_win ||
            $score_team_2 >= $this->points_to_win
        ) {
            if (abs($score_team_1 - $score_team_2) >= 2) {
                $this->set_status('closed');
            }
        }

        return [
            'success' => true,
            'data' => $this->get_game_data()
        ];
    }
}
