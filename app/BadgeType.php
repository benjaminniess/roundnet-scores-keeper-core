<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BadgeType extends Model
{
	protected $table = 'badges_types';

    const GAME_BADGE_TYPE = "game_badge";
    const FRIEND_BADGE_TYPE = "friend_badge";
    const VICTORY_BADGE_TYPE = "victory_badge";
    const VICTORIES_SERIES_BADGE_TYPE = "victories_series";

    /**
     * Get all badges associated to the badge type
     * 
     */
    public function badges() {
    	return $this->hasMany('\App\Badge', 'badges_types_id');
    }
}
