<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    protected $fillable = [
        'player1',
        'player2',
        'player3',
        'player4'
    ];

    /**
     * Get all points associated to the given game
     *
     * @return mixed
     */
    public function get_history() {
        $game_points = Game_Point::where( [
            [ 'game_id', $this->id ],
        ])->get();

        return $game_points;
    }

    /**
     * Verify that a given player is in the current game
     *
     * @param $player_id
     * @return bool
     */
    public function is_player_in_game( $player_id ) {
        if ( (int) $player_id !== (int) $this->player1 && (int) $player_id !== (int) $this->player2 && (int) $player_id !== (int) $this->player3 && (int) $player_id !== (int) $this->player4 ) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the game status is live
     *
     * @return boolean
     */
    public function is_game_live() {
        return $this->status === 'live' ? true : false;
    }

    /**
     * Get the current game scores
     *
     * @return array
     */
    public function get_scores() {
        return [
            'team1' => $this->score_team_1,
            'team2' => $this->score_team_2,
        ];
    }

    /**
     * Get the 4 players \App\User objects
     *
     * @return array
     */
    public function get_players() {
        return [
            'player1' => \App\User::find( $this->player1 ),
            'player2' => \App\User::find( $this->player2 ),
            'player3' => \App\User::find( $this->player3 ),
            'player4' => \App\User::find( $this->player4 ),
        ];
    }

    /**
     * Prepare all game data for API usage
     *
     * @return array
     */
    public function get_game_data() {
        $players = $this->get_players();

        return [
            'success' => true,
            'data'    => [
                'teams'    => [
                    'a' => [
                        'players' => [
                            'p1' => $players['player1']->get_user_data(),
                            'p2' => $players['player2']->get_user_data(),
                        ],
                    ],
                    'b' => [
                        'players' => [
                            'p3' => $players['player3']->get_user_data(),
                            'p4' => $players['player4']->get_user_data(),
                        ],
                    ],
                ],
                'points'   => $this->get_history(),
                'score'    => $this->get_scores(),
                'duration' => $this->game_duration,
            ],
        ];
    }

    /**
     * Update game current score
     *
     * @param int $score_team_1
     * @param int $score_team_2
     */
    public function set_score( $score_team_1 = 0, $score_team_2 = 0 ) {
        DB::table('games')->where('id', $this->id)->update([
            'score_team_1' => $score_team_1,
            'score_team_2' => $score_team_2,
        ]);
    }

    /**
     * Change game status
     *
     * @param $status
     * @return int
     */
    public function set_status( $status ) {
        if( ! DB::table('games')->where('id', $this->id)->update([
            'status' => 'closed',
        ]) ) {
            return false;
        }

        $this->status = $status;
    }

    /**
     * Ads a new point history to the game
     *
     * @param $player_id
     * @param $action_type_id
     * @return array
     */
    public function add_point( $player_id, $action_type_id ) {
        if ( ! $this->is_game_live() ) {
            return [
                'success' => false,
                'code' => 'game-not-live'
            ];
        }

        if ( ! $this->is_player_in_game( $player_id ) ) {
            return [
                'success' => false,
                'code' => 'not-in-game'
            ];
        }

        $action_type = DB::table('actions_types')->where('id', '=', $action_type_id)->first();
        if ( empty( $action_type ) ) {
            return [
                'success' => false,
                'code' => 'wrong-action-type'
            ];
        }

        $score_team_1 = $this->getAttribute( 'score_team_1' );
        $score_team_2 = $this->getAttribute( 'score_team_2' );
        // Player is in team 1 ?
        if ( $player_id === $this->player_1 || $player_id === $this->player_1 ) {
            if ( 'positive' === $action_type->action_type ) {
                $score_team_1 ++;
            } elseif ( 'negative' === $action_type->action_type ) {
                $score_team_2 ++;
            }
        } else {
            if ( 'positive' === $action_type->action_type ) {
                $score_team_2 ++;
            } elseif ( 'negative' === $action_type->action_type ) {
                $score_team_1 ++;
            }
        }

        $this->set_score( $score_team_1, $score_team_2 );

        DB::table('game_points')->insert(
            [
                'player_id'      => $player_id,
                'action_type_id' => $action_type_id,
                'score_team_1'   => $score_team_1,
                'score_team_2'   => $score_team_2,
                'game_id'        => $this->id,
            ]
        );

        if ( $score_team_1 >= 21 || $score_team_2 >= 21 ) {
            if ( abs( $score_team_1 - $score_team_2 ) >= 2 ) {
                $this->set_status( 'closed' );
                $this->fresh();
            }
        }

        return [
            'success' => true,
            'data'    => [
                'game_status' => $this->status,
                'score'       => $this->get_scores(),
            ],
        ];
    }
}
