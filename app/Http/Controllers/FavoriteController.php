<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Cart;
use App\Item;
use App\Http\Requests;

class FavoriteController extends Controller
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

        $items = $cart->favoriteItems()->get();

        return response()->json(['success' => true, 'items' => $items]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        if (Auth::user()->cant('write', $cart)) {
            return response()->json(['success' => false], 403);
        }

        $this->validate($request, [
            'items' => 'required',
        ]);

        $cart->items()
             ->whereIn('id', explode(',', $request->items))
             ->update(['visible' => true]);

        return ['success' => true];
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
             ->whereIn('id', explode(',', $request->items))
             ->update(['count' => 0]);

        return ['success' => true];
    }

}
