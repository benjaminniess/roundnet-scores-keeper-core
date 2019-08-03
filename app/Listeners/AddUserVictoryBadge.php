<?php

namespace App\Listeners;

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
    public function OnGameHasEnded($event)
    {
        $badges = Badge::badges( BadgeType::VICTORY_BADGE_TYPE );
        $game = $event->game;
        $players = $game->players;

        /**
         * Foreach App\User check if the percentage of victory match with a badge objective (action_count)
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

    /**
     * Handle the event.
     *
     * @param  GameWasDeleted  $event
     * @return void
     */
    public function OnGameWasDeleted($event)
    {
        $badges = Badge::badges( BadgeType::VICTORY_BADGE_TYPE );
        $game = $event->game;
        $players = $event->players;

        /**
         * Foreach App\User check if the percentage of victory match with a badge objective (action_count)
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

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\GameHasEnded',
            'App\Listeners\AddUserVictoryBadge@OnGameHasEnded'
        );

        $events->listen(
            'App\Events\GameWasDeleted',
            'App\Listeners\AddUserVictoryBadge@OnGameWasDeleted'
        );
    }
}
