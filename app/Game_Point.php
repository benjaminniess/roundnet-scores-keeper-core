<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Game_Point extends Model
{
    protected $table = 'game_points';
    //

    public function game() {
        return $this->belongsTo( '\App\Game', 'game_id')->first();
    }

    /**
     * Get the duration in seconds of a rally
     *
     * @return bool|string
     */
    public function get_duration() {
        if ( empty( $this->created_at->timestamp ) || empty( $this->game()->start_date ) ) {
            return false;
        }

        return $this->created_at->timestamp - ( $this->game()->start_date / 1000 ) . 's';
    }

    public function get_point_owner() {

        $player_obj = User::find($this->player_id);

        return $player_obj;
    }

    public function get_point_action_type() {

        $action_type_obj = Action_Type::find($this->action_type_id);

        return $action_type_obj;
    }
}
