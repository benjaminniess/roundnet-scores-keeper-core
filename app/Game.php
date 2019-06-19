<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
