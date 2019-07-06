<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GuestInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\User $guest_obj, \App\User $user_obj)
    {
        $this->guest_obj = $guest_obj;
        $this->user_obj = $user_obj;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.guest.invitation')->with([
            'inviter_name' => $this->user_obj->name,
            'guest_name'   => $this->guest_obj->name,
            'resetkey'     => 1234,
        ]);
    }
}
