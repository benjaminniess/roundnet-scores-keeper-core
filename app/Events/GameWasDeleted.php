<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GameWasDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $game;
    public $players_user_obj;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $game, $players_user_obj )
    {
        $this->game = $game;
        $this->players = $players_user_obj;
    }
}
