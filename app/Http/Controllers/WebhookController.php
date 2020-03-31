<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Events\ItemCreated;
use App\Http\Requests\WebhookRequest;
use App\Models\Item;
use App\Models\User;

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

        $items = $request->getItems()->map(function ($itemName, $key) use ($cart) {
            $item = $cart->findOrNew(trim($itemName));
            $item->name = trim($itemName);
            $item->done = false;
            $item->visible = true;
            $item->count = $item->count + 1;
            $item->save();

            return $item;
        });

        if ($items->isNotEmpty() && $cart->shared) {
            broadcast(new ItemCreated($cart, $items->all()));
        }

        return ['success' => true];
    }
}
