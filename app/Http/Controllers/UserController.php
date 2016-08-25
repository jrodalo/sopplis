<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Hash;
use Event;
use App\User;
use App\Http\Requests;
use App\Events\UserReturned;
use App\Events\UserWasCreated;
use Illuminate\Auth\AuthenticationException;

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

        return response()->json(['success' => true, 'name' => $user->name, 'token' => $user->api_token]);
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
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (is_null($user))
        {
            $user = new User;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->name = strstr($request->email, '@', true);
            $user->api_token = str_random(60);
            $user->save();

        } else {

            if ( ! Hash::check($request->password, $user->password))
            {
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
        $this->validate($request, [
            'name' => 'required|max:100',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return ['success' => true];
    }
}
