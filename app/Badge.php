<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Badge extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * Get all badges
     *
     * @param $badge_type
     * @return collection
     */
    public static function badges ( $badge_type = null ) {
    	$query = Badge::select('badges.*');
    	if ( !is_null($badge_type) ) {
    		$query
    		->join( 'badges_types', 'badges.badges_types_id', '=', 'badges_types.id' )
    		->where('badges_types.name', '=', $badge_type);
    	}
    	return $query->get();
    }

    /**
     * Get a collection of badges
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function users_badges () {
    	return $this->belongsToMany('App\User', 'users_badges', 'badge_id', 'user_id');
    }

    /**
     * Add the badge to a user
     *
     * @param $user_id
     * 
     */
    public function add_user_badge ( $user_id ) {
    	$this
        ->users_badges()
        ->attach( $user_id );
    }
}
