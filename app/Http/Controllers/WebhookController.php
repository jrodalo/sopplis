<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Events\ItemCreated;
use App\Http\Requests\WebhookRequest;
use App\Item;
use App\User;

class WebhookController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebhookRequest $request)
    {
        abort_if($request->notValid(), 406);

        $user = User::where('email', $request->getUserEmail())->first();
        abort_if(is_null($user), 406);

        $cart = $user->carts()->where('slug', $request->getCartSlug())->first();
        abort_if(is_null($cart) || $user->cant('write', $cart), 406);

        $items = $request->getItems()->map(function($itemName, $key) use ($cart) {

            $item = $cart->items()->where('name', 'LIKE', trim($itemName))->first();

            if (is_null($item)) {
                $item = new Item;
                $item->cart_id = $cart->id;
                $item->count = 0;
            }

            $item->name = trim($itemName);
            $item->done = false;
            $item->visible = true;
            $item->count = $item->count + 1;
            $item->save();
            return $item;
        });

        if ($cart->shared && $items->isNotEmpty()) {
            broadcast(new ItemCreated($cart, $items->all()));
        }

        return ['success' => true];
    }

}
