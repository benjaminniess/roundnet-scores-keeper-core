<?php

namespace App\Listeners;

use App\Events\GameHasEnded;
use App\Badge;
use App\BadgeType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\BadgeUnlocked;
use App\Notifications\BadgeRemoved;

class AddUserVictoryBadge
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
     * @param  GameHasEnded  $event
     * @return void
     */
    public function handle(GameHasEnded $event)
    {
        $badges = Badge::badges( BadgeType::VICTORY_BADGE_TYPE );
        $game = $event->game;
        $players = $game->players;

        /**
         * Foreach App\User check if the total of closed played games match with a badge objective (action_count)
         * If it does, register a connexion between the badge and the user in the database
         * If the user already has the badge, don't register it
         */
        foreach ($players as $player) {
            $player->percentage_victory = $player->percentage_victory();
            foreach ( $badges as $badge ) {
                if ( (int) $player->percentage_victory >= (int) $badge->action_count && !$player->has_badge( $badge->id ) ) {
                    $badge->add_user_badge( $player->id );
                    $player->notify( new BadgeUnlocked( $badge ) );
                } elseif ( $player->has_badge( $badge->id ) && (int) $player->percentage_victory < (int) $badge->action_count ) {
                    $badge->remove_user_badge( $player->id );
                    $player->notify( new BadgeRemoved( $badge ) );
                }
            }
        }
        return;
    }
}
