<?php

namespace App\Listeners;

use App\Events\AFriendRequestHasBeenAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Badge;
use App\BadgeType;
use App\UserRelationships;

class AddUserFriendBadge
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AFriendRequestHasBeenAccepted  $event
     * @return void
     */
    public function handle(AFriendRequestHasBeenAccepted $event)
    {
        $relationship = $event->relationship;
        $badges = Badge::badges( BadgeType::FRIEND_BADGE_TYPE );
        $friends = $relationship->friends();

        /**
         * Foreach App\User check if the total of active friends match with a badge objective (action_count)
         * If it does, register a connexion between the badge and the user in the database
         * If the user already has the badge, don't register it
         */
        foreach ($friends as $friend) {
            $friend->total_friends = $friend->total_friends( UserRelationships::ACTIVE_STATUS );
            foreach ( $badges as $badge ) {
                if ( (int) $badge->action_count === (int) $friend->total_friends && !$friend->has_badge( $badge->id ) ) {
                    $badge->add_user_badge( $friend->id );
                }
            }
        }
        return;
    }
}
