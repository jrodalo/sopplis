<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use Auth;
use App\Cart;
use App\User;
use App\Http\Requests;
use App\Mail\CartShared;

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

        $owner = Auth::user();

        $cart = new Cart;
        $cart->name = $request->name;
        $cart->slug = str_random(6);
        $cart->save();
        $cart->users()->attach($owner, ['role' => 'owner']);

        return ['success' => true, 'cart' => $cart];
    }

}
