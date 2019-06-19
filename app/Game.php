<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    protected $table = 'games';
    //

    /**
     * Get all points associated to the given game
     *
     * @return mixed
     */
    public function get_history() {
        $game_points = Game_Point::where( [
            [ 'game_id', $this->getAttribute('id') ],
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
        if ( (int) $player_id !== (int) $this->getAttribute( 'player1' ) && (int) $player_id !== (int) $this->getAttribute( 'player2' ) && (int) $player_id !== (int) $this->getAttribute( 'player3' ) && (int) $player_id !== (int) $this->getAttribute( 'player4' ) ) {
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
        return $this->getAttribute( 'status' ) === 'live' ? true : false;
    }

    /**
     * Get the current game scores
     *
     * @return array
     */
    public function get_scores() {
        return [
            'team1' => $this->getAttribute( 'score_team_1' ),
            'team2' => $this->getAttribute( 'score_team_2' ),
        ];
    }

    /**
     * Update game current score
     *
     * @param int $score_team_1
     * @param int $score_team_2
     */
    public function set_score( $score_team_1 = 0, $score_team_2 = 0 ) {
        DB::table('games')->where('id', $this->getAttribute('id'))->update([
            'score_team_1' => $score_team_1,
            'score_team_2' => $score_team_2,
        ]);
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
        if ( $player_id === $this->getAttribute( 'player_1' ) || $player_id === $this->getAttribute( 'player_1' ) ) {
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
            ]
        );

        return [
            'success' => true,
        ];
    }
}
