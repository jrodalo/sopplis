<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Cart;
use App\User;
use App\Http\Requests;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(['success' => true, 'lists' => Auth::user()->carts()->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
        ]);

        $cart = new Cart;
        $cart->name = $request->name;
        $cart->save();
        $cart->users()->attach(Auth::user());

        return ['success' => true, 'cart' => $cart];
    }

}
