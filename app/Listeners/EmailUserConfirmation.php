<?php

namespace App\Listeners;

use Mail;
use App\Events\UserWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUserConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  UserWasCreated  $event
     * @return void
     */
    public function handle(UserWasCreated $event)
    {
        $user = $event->user;

        Mail::queue('emails.welcome', ['user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Bienvenid@ a Sopplis');
        });
    }
}
