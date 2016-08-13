<?php

namespace App\Listeners;

use Mail;
use App\Events\UserReturned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUserKey
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
     * @param  UserReturned  $event
     * @return void
     */
    public function handle(UserReturned $event)
    {
        $user = $event->user;

        Mail::queue('emails.returned', ['user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Bienvenid@ de nuevo a Sopplis');
        });
    }
}
