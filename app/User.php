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
}
