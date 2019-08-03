<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Events\GameHasEnded;
use App\Events\AFriendRequestHasBeenAccepted;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\AddUserGameBadge;
use App\Listeners\AddUserFriendBadge;
use App\Listeners\AddUserVictoryBadge;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        GameHasEnded::class => [
            AddUserGameBadge::class,
            AddUserVictoryBadge::class,
        ],

        AFriendRequestHasBeenAccepted::class => [
            AddUserFriendBadge::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
