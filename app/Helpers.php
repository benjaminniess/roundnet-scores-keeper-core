<?php
namespace App;

class Helpers
{
    public static function nickname_exists($nickname)
    {
        $user_obj = \App\User::where('name', '=', $nickname)->first();

        return !empty($user_obj);
    }

    public static function create_guest_account($nickname, $friend_with)
    {
        $friend_obj = \App\User::find($friend_with);
        if (empty($friend_obj)) {
            return false;
        }

        $guest = new \App\User();
        $guest->name = filter_var($nickname, FILTER_SANITIZE_STRING);
        $guest->password = 'guestpassword';
        $guest->email =
            time() . '-' . rand(10000, 90000) . '@roundnet-scores-keeper.com';
        $guest->save();

        $relationship = new \App\UserRelationships();
        $relationship->user_id_1 = $friend_obj->id;
        $relationship->user_id_2 = $guest->id;
        $relationship->status = 'guest';
        $relationship->save();

        return $guest->id;
    }
}
