<?php

namespace App\Http\Controllers;

use App\Events\UserReturned;
use App\Events\UserWasCreated;
use App\Http\Requests;
use App\User;
use Auth;
use Event;
use Hash;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            $user = new User;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->name = strstr($request->email, '@', true);
            $user->api_token = str_random(60);
            $user->save();
        } else {
            if (! Hash::check($request->password, $user->password)) {
                throw new AuthenticationException();
            }
        }

        return ['success' => true, 'name' => $user->name, 'token' => $user->api_token];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return ['success' => true];
    }
}
