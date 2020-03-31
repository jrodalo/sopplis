<?php

namespace App\Mail;

use App\Cart;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CartShared extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $owner;
    public $guest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Cart $cart, User $owner, User $guest)
    {
        $this->cart = $cart;
        $this->owner = $owner;
        $this->guest = $guest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Te han invitado a una lista compartida en Sopplis')
                    ->view('emails.guests');
    }
}
