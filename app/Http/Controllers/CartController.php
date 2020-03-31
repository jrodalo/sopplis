<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Requests;
use App\Mail\CartShared;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Mail;

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
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $owner = Auth::user();

        $cart = new Cart;
        $cart->name = $request->name;
        $cart->slug = str_random(6);
        $cart->save();
        $cart->users()->attach($owner, ['role' => 'owner']);

        return ['success' => true, 'list' => $cart];
    }
}
