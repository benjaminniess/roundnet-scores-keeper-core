<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\DB;

class User_Relationships extends Model
{
    protected $table = 'user_relationships';

    private $_status;

    const ACTIVE_STATUS = 'active';
    const PENDING_STATUS = 'pending';
    const BLOCKED_STATUS = 'blocked';

    /**
    * Get all authenticated user friends
    *
    *@return object
    */
    public function get_auth_user_friends($_status) {

        $auth_user_friends = DB::table('users')
        ->select('users.id','users.name','users.email', 'user_relationships.status')
        ->join('user_relationships', function($join){
            $join->on('users.id', '=', 'user_relationships.user_id_1')
                 ->orOn('users.id', '=', 'user_relationships.user_id_2');
        })
        ->where('users.id', '<>', auth()->id())
        ->where('user_relationships.status', '=', $_status)
        ->where(function($query){
            $query->where('user_relationships.user_id_1', '=', auth()->id())
                  ->orWhere('user_relationships.user_id_2', '=', auth()->id());
        })
        ->groupBy('users.id')
        ->get();

        return $auth_user_friends;
    }
}
