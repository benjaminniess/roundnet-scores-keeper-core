<?php

namespace App\Listeners;

use App\Events\GameHasEnded;
use App\Badge;
use App\BadgeType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\BadgeUnlocked;

class AddUserGameBadge
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
        $badges = Badge::badges( BadgeType::GAME_BADGE_TYPE );
        $game = $event->game;
        $players = $game->players;

        /**
         * Foreach App\User check if the total of closed played games is higher or equal than the badge objective (action_count)
         * If it does, register a connexion between the badge and the user in the database
         * If the user already has the badge, don't register it
         */
        foreach ($players as $player) {
            $player->total_games = $player->total_games( $status = 'closed' );
            foreach ( $badges as $badge ) {
                if ( (int) $player->total_games >= (int) $badge->action_count && !$player->has_badge( $badge->id ) ) {
                    $badge->add_user_badge( $player->id );
                    $player->notify( new BadgeUnlocked( $badge ) );
                }
            }
        }
        return;
    }
}
