<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Cart;
use App\Item;
use App\Http\Requests;

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
        if (Auth::user()->cant('read', $cart)) {
            return response()->json(['success' => false], 403);
        }

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
        if (Auth::user()->cant('write', $cart)) {
            return response()->json(['success' => false], 403);
        }

        $this->validate($request, [
            'name' => 'required|max:100',
        ]);

        $item = $cart->items()->where('name', 'LIKE', $request->name)->first();

        if (is_null($item)) {
            $item = new Item;
            $item->cart_id = $cart->id;
            $item->count = 0;
        }

        $item->name = $request->name;
        $item->done = false;
        $item->visible = true;
        $item->count = $item->count + 1;
        $item->save();

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
        if (Auth::user()->cant('write', $cart)) {
            return response()->json(['success' => false], 403);
        }

        $item->done = $request->done;

        $item->save();

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
        if (Auth::user()->cant('write', $cart)) {
            return response()->json(['success' => false], 403);
        }

        $this->validate($request, [
            'items' => 'required',
        ]);

        $cart->items()
             ->where('done', true)
             ->whereIn('id', explode(',', $request->items))
             ->update(['visible' => false, 'done' => false]);

        return ['success' => true];
    }

}
