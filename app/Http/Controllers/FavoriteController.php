<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Events\ItemCreated;
use App\Http\Requests;
use App\Models\Item;
use Auth;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Cart $cart)
    {
        abort_if(Auth::user()->cant('read', $cart), 403);

        $items = $cart->favoriteItems()->get();

        return response()->json(['success' => true, 'items' => $items]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        abort_if(Auth::user()->cant('write', $cart), 403);

        $request->validate([
            'items' => 'required',
        ]);

        $query = $cart->items()->whereIn('id', explode(',', $request->items));

        $query->update(['visible' => true]);

        if ($cart->shared) {
            broadcast(new ItemCreated($cart, $query->get()->toArray()))->toOthers();
        }

        return ['success' => true];
    }

    /**
     * Remove the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Cart $cart)
    {
        abort_if(Auth::user()->cant('write', $cart), 403);

        $request->validate([
            'items' => 'required',
        ]);

        $cart->items()
             ->whereIn('id', explode(',', $request->items))
             ->update(['count' => 0]);

        return ['success' => true];
    }
}
