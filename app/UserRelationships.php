<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRelationships extends Model
{
    protected $fillable = ['user_id_1', 'user_id_2', 'status'];

    const ACTIVE_STATUS = 'active';
    const GUEST_STATUS = 'guest';
    const PENDING_STATUS = 'pending';
    const BLOCKED_STATUS = 'blocked';
    const DECLINED_STATUS = 'declined';

    public function get_status()
    {
        return $this->status;
    }

    public function update_status($status)
    {
        return $this->update($status);
    }

    public function destroy_relationship()
    {
        $this->delete();
    }

    /**
     * Get both users in a relationship
     * 
     * @return a collection of App\User
     */
    public function friends () {
        return User::select()
        ->where('id', '=', $this->user_id_1)
        ->orWhere('id', '=', $this->user_id_2)
        ->get();
    }
}
