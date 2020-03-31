<?php

namespace App\Events;

use App\Cart;
use App\Item;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $cart;
    public $items;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Cart $cart, array $items)
    {
        $this->cart = $cart;
        $this->items = $items;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('lists.'.$this->cart->slug);
    }
}
