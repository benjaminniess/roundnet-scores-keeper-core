<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRelationships extends Model
{
    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'status'
    ];

    const ACTIVE_STATUS = 'active';
    const PENDING_STATUS = 'pending';
    const BLOCKED_STATUS = 'blocked';
    const DECLINED_STATUS = 'declined';

    public function get_status(){
        return $this->status;
    }

    public function update_status($status){
        return $this->update($status);
    }
}
