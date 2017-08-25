<?php

namespace App\Events;

use App\Cart;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartFinished implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $cart;
    private $items;
    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Cart $cart, $items, User $user)
    {
        $this->cart = $cart;
        $this->items = $items;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('lists.' . $this->cart->slug);
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'items' => $this->items,
            'user' => $this->user->name
        ];
    }

}
