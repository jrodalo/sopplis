<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Http\Requests;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function readToken(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|max:100',
        ]);

        $user = User::where('remember_token', $request->token)->firstOrFail();
        $user->remember_token = '';
        $user->save();

        return response()->json(['success' => true, 'token' => $user->api_token]);
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
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            $user = new User;
            $user->email = $request->email;
            $user->name = strstr($request->email, '@', true);
            $user->api_token = str_random(60);
        }

        $user->remember_token = str_random(60);
        $user->save();

        // send emails

        return ['success' => true];
    }

}
