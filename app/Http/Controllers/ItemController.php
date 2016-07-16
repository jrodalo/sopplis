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

        $items = $request->favorite === true ?
                 $cart->favoriteItems()->get() :
                 $cart->visibleItems()->get();

        return response()->json(['success' => true, 'items' => $items]);
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

        $item = new Item;
        $item->name = $request->name;
        $item->done = false;
        $item->visible = true;
        $item->cart_id = $cart->id;
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

}
