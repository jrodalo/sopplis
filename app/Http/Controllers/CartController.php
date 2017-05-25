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
            'emails' => 'array|max:5',
            'emails.*' => 'email',
        ]);

        $owner = Auth::user();

        $cart = new Cart;
        $cart->name = $request->name;
        $cart->slug = str_random(6);
        $cart->save();
        $cart->users()->attach($owner, ['role' => 'owner']);

        foreach($request->emails as $email)
        {
            if ($email == $owner->email) {
                continue;
            }

            $guest = User::where('email', $email)->first();

            if (is_null($guest)) {
                $guest = new User;
                $guest->email = $email;
                $guest->name = strstr($email, '@', true);
                $guest->api_token = str_random(60);
            }

            $guest->remember_token = str_random(60);
            $guest->save();

            $cart->users()->attach($guest, ['role' => 'guest']);

            Mail::to($guest->email)->queue(new CartShared($cart, $owner, $guest));
        }

        return ['success' => true, 'cart' => $cart];
    }

}
