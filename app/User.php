<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Checks in cookies and returns a valid token for the current user. If no access token, it will generate a new one
     *
     * @return mixed
     */
    public function get_access_token( $new = false) {
        if ( false === $new && isset( $_COOKIE['user_access_token'] ) && ! empty( $_COOKIE['user_access_token'] ) ) {
            return $_COOKIE['user_access_token'];
        }

        DB::table('oauth_access_tokens')->where([
            [ 'user_id', '=', $this->getAttribute( 'id' ) ],
            [ 'name', '=', 'ReactToken' ],
        ], '=', $this->getAttribute( 'id' ))->delete();
        $access_token = Auth::user()->createToken('ReactToken')->accessToken;
        setcookie('user_access_token', $access_token, time() + 30 * 3600 * 24, '/' );

        return $access_token;
    }

    public function games() {
        return $this->belongsToMany('App\Game', 'players', 'user_id', 'game_id');
    }

    /**
     * Checks if the given user is in a live game and returns it
     *
     * @return \App\Game
     */
    public function get_live_game() {
        return $this->games()->where('status', '=', 'live')->first();
    }

    /**
     * Checks if the user is already in a live game
     *
     * @return bool
     */
    public function is_in_a_live_game() {
        return ! empty( $this->get_live_game() );
    }

    /**
     * Prepare user data for rest API usage
     *
     * @return array
     */
    public function get_user_data() {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'avatar' => 'comming-soon',
        ];
    }

    /**
    * Get all user friends
    *
    *@return object
    */
    public function friends($status = '') {
	    $query_a_to_b = $this->belongsToMany('App\User', 'user_relationships', 'user_id_1', 'user_id_2');
	    $query_b_to_a = $this->belongsToMany('App\User', 'user_relationships', 'user_id_2', 'user_id_1');

	    if ( ! empty( $status ) ) {
            $query_a_to_b->where( 'status', '=', $status );
            $query_b_to_a->where( 'status', '=', $status );
	    }

	    return $query_a_to_b->get()->merge( $query_b_to_a->get() );
    }

    /**
     * Get incoming friend requests
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get_friend_requests() {
        return $this->belongsToMany('App\User', 'user_relationships', 'user_id_2', 'user_id_1')
            ->where( 'status', '=', 'pending' )
            ->get();
    }


    /**
    * Get one user relationship info
    *
    *@return object
    */
    public function get_relationship($friend_id) {

        $relationship = UserRelationships::select()
        ->where(function($query) use($friend_id){
            $query->where('user_relationships.user_id_1', '=', $this->id)
                  ->orWhere('user_relationships.user_id_1', '=', $friend_id);
        })->where(function($query) use($friend_id){
            $query->where('user_relationships.user_id_2', '=', $this->id)
                  ->orWhere('user_relationships.user_id_2', '=', $friend_id);
        });

        return $relationship->first();
    }

    public function is_friend($friend_id){
        if (empty($this->get_relationship($friend_id))) {
            return false;
        }
        return true;
    }
}
