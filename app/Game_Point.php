<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Notifications\Action;

class Game_Point extends Model
{
    protected $table = 'game_points';
    //

    public function game()
    {
        return $this->belongsTo('\App\Game', 'game_id')->first();
    }

    /**
     * Get the duration in seconds of a rally
     *
     * @return bool|string
     */
    public function get_duration()
    {
        $previous_point_obj = Game_Point::where(
            'created_at',
            '<',
            $this->created_at
        )
            ->orderBy('created_at', 'desc')
            ->first();

        $current_point = $this->created_at;

        if (!empty($previous_point_obj)) {
            $previous_point = $previous_point_obj->created_at;
        } else {
            $previous_point = $this->game()->created_at;
        }

        $duration_obj = $current_point->diff($previous_point);

        $duration = '';

        $duration .= (int) $duration_obj->h > 0 ? $duration_obj->h . 'h' : '';
        $duration .= (int) $duration_obj->m > 0 ? $duration_obj->m . 'm' : '';
        $duration .= !empty($duration) && !empty($duration_obj->s) ? ':' : '';
        $duration .= (int) $duration_obj->s > 0 ? $duration_obj->s . 's' : '';

        return $duration;
    }

    /**
     * Get the attached point player
     *
     * @return \App\Models\User
     */
    public function get_point_owner()
    {
        $player_obj = User::find($this->player_id);

        return $player_obj;
    }

    /**
     * Get the related action type object
     *
     * @return Action_Type
     */
    public function get_point_action_type()
    {
        $action_type_obj = Action_Type::find($this->action_type_id);

        return $action_type_obj;
    }
}
