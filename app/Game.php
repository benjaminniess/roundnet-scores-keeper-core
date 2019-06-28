<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    protected $guarded = [
        'id'
    ];

    /**
     * Get all points associated to the given game
     *
     */
    public function history() {
        return $this->hasMany('\App\Game_Point', 'game_id');
    }

    /**
     * Return the game start date if exists
     *
     * @return bool|false|string
     */
    public function get_date() {
        $start_date = $this->start_date;
        if ( 0 >= (int) $start_date ) {
            return false;
        }

        return date('Y-m-d H:i', $start_date / 1000 );
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
     */
    public function players() {
        return $this->belongsToMany('App\User', 'players', 'game_id','user_id' )->withPivot('position');
    }

    /**
     * Prepare all game data for API usage
     *
     * @return array
     */
    public function get_game_data() {
        $players = $this->players;

        $game_data = [
            'id'             => (int) $this->id,
            'teams'          => [
                'a' => [
                    'players' => [
                        'p1' => false,
                        'p2' => false,
                    ],
                ],
                'b' => [
                    'players' => [
                        'p3' => false,
                        'p4' => false,
                    ],
                ],
            ],
            'points'         => $this->history,
            'score'          => $this->get_scores(),
            'start_date'     => (int) $this->start_date,
            'current_server' => (int) $this->current_server,
            'points_to_win'  => (int) $this->points_to_win,
            'enable_turns'   => (int) $this->enable_turns,
            'status'         => $this->status,
        ];

        foreach ($players as $player) {
            switch( $player->pivot->position ) {
                case '1':
                    $game_data['teams']['a']['players']['p1'] = $player->get_user_data();
                    break;
                case '2':
                    $game_data['teams']['a']['players']['p2'] = $player->get_user_data();
                    break;
                case '3':
                    $game_data['teams']['b']['players']['p3'] = $player->get_user_data();
                    break;
                case '4':
                    $game_data['teams']['b']['players']['p4'] = $player->get_user_data();
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
    public function get_actions_types() {
        return [
            'positive' => DB::table('actions_types')->where( 'action_type', 'positive' )->get(),
            'neutral'  => DB::table('actions_types')->where( 'action_type', 'neutral' )->get(),
            'negative' => DB::table('actions_types')->where( 'action_type', 'negative' )->get(),
        ];
    }

    /**
     * Update game current score
     *
     * @param int $score_team_1
     * @param int $score_team_2
     */
    public function set_score( $score_team_1 = 0, $score_team_2 = 0 ) {
        if( ! DB::table('games')->where('id', $this->id)->update([
            'score_team_1' => $score_team_1,
            'score_team_2' => $score_team_2,
        ]) ) {
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
    public function set_status( $status ) {
        if( ! DB::table('games')->where('id', $this->id)->update([
            'status' => 'closed',
        ]) ) {
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

        if ( (int) $player_id === (int) $this->player1 || (int) $player_id === (int) $this->player2 ) {
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

        if ( $score_team_1 >= $this->points_to_win || $score_team_2 >= $this->points_to_win ) {
            if ( abs( $score_team_1 - $score_team_2 ) >= 2 ) {
                $this->set_status( 'closed' );
            }
        }

        return [
            'success' => true,
            'data'    => $this->get_game_data(),
        ];
    }
}
