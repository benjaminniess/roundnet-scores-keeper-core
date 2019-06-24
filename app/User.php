<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \TCG\Voyager\Models\User
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
    public function get_access_token() {
        if ( isset( $_COOKIE['user_access_token'] ) && ! empty( $_COOKIE['user_access_token'] ) ) {
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

    /**
     * Checks if the given user is in a live game and returns it
     *
     * @return \App\Game
     */
    public function get_live_game() {
        $user_id = $this->getAttribute( 'id' );

        $game_live = Game::where( [
            [ 'status', 'live' ],

        ])->where(function ($query) use ($user_id) {
            $query->where( 'player1', '=', $user_id )->orWhere( 'player2', '=', $user_id)->orWhere( 'player3', '=', $user_id)->orWhere( 'player4', '=', $user_id);
        })->first();

        return $game_live;
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
    public function get_friends($status) {

        $auth_user_friends = DB::table('users')
        ->select('users.id','users.name','users.email', 'user_relationships.status')
        ->join('user_relationships', function($join){
            $join->on('users.id', '=', 'user_relationships.user_id_1')
                 ->orOn('users.id', '=', 'user_relationships.user_id_2');
        })
        ->where('users.id', '<>', $this->id)
        ->where('user_relationships.status', '=', $status)
        ->where(function($query){
            $query->where('user_relationships.user_id_1', '=', $this->id)
                  ->orWhere('user_relationships.user_id_2', '=', $this->id);
        })
        ->groupBy('users.id')
        ->get();

        return $auth_user_friends;
    }


        /**
        * Get one user relationship info
        *
        *@return object
        */
        public function get_relationship($friend_id) {

            $relationship = DB::table('user_relationships')
            ->select()
            ->where(function($query) use($friend_id){
                $query->where('user_relationships.user_id_1', '=', $this->id)
                      ->orWhere('user_relationships.user_id_1', '=', $friend_id);
            })->where(function($query) use($friend_id){
                $query->where('user_relationships.user_id_2', '=', $this->id)
                      ->orWhere('user_relationships.user_id_2', '=', $friend_id);
            })
            ->first();

            return $relationship;
        }
}
