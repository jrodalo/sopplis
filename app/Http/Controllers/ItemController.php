<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Events\CartFinished;
use App\Events\ItemCreated;
use App\Events\ItemUpdated;
use App\Http\Requests;
use App\Item;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Cart $cart)
    {
        abort_if(Auth::user()->cant('read', $cart), 403);

        $items = $cart->visibleItems()->get();

        return response()->json(['success' => true, 'list' => $cart, 'items' => $items]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Cart $cart)
    {
        abort_if(Auth::user()->cant('write', $cart), 403);

        $request->validate([
            'name' => 'required|max:100',
        ]);

        $item = $cart->findOrNew(trim($request->name));
        $item->name = trim($request->name);
        $item->done = false;
        $item->visible = true;
        $item->count = $item->count + 1;
        $item->save();

        if ($cart->shared) {
            broadcast(new ItemCreated($cart, [$item]))->toOthers();
        }

        return ['success' => true, 'item' => $item];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Cart  $cart
     * @param  App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart, Item $item)
    {
        abort_if(Auth::user()->cant('write', $cart), 403);

        $item->done = $request->done;

        $item->save();

        if ($cart->shared) {
            broadcast(new ItemUpdated($cart, $item))->toOthers();
        }

        return ['success' => true, 'item' => $item];
    }

    /**
     * Remove the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Cart $cart)
    {
        abort_if(Auth::user()->cant('write', $cart), 403);

        $request->validate([
            'items' => 'required',
        ]);

        $cart->items()
             ->where('done', true)
             ->whereIn('id', explode(',', $request->items))
             ->update(['visible' => false, 'done' => false]);

        if ($cart->shared) {
            broadcast(new CartFinished($cart, $request->items, Auth::user()))->toOthers();
        }

        return ['success' => true, 'message' => $this->randomSuccessMessage()];
    }

    /**
     * Get a random success message
     *
     * @return string
     */
    public static function randomSuccessMessage()
    {
        return Collection::make([
            '¡Hasta la próxima!',
            '¡Buena compra!',
            '¡Por fin! :)',
            '¡Perfecto!',
            '¡A casa!',
            '¡Genial!',
        ])->random();
    }

}
