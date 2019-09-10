<?php

namespace App\Listeners;

use App\Events\GameHasEnded;
use App\Badge;
use App\BadgeType;
use App\Notifications\BadgeUnlocked;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUserVictoriesSeriesBadge
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
        $badges = Badge::badges( BadgeType::VICTORIES_SERIES_BADGE_TYPE );
        $game = $event->game;
        $players = $game->players;

        /**
         * Foreach App\User check if the total of closed played games is higher or equal than the badge objective (action_count)
         * If it does, register a connexion between the badge and the user in the database
         * If the user already has the badge, don't register it
         */
        foreach ($players as $player) {
            $player->victories_series = $player->get_best_series_of_victories();
            foreach ( $badges as $badge ) {
                if ( (int) $player->victories_series >= (int) $badge->action_count && !$player->has_badge( $badge->id ) ) {
                    $badge->add_user_badge( $player->id );
                    $player->notify( new BadgeUnlocked( $badge ) );
                }
            }
        }
        return;
    }
}
