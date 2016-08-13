<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Event;
use App\User;
use App\Http\Requests;
use App\Events\UserWasCreated;

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

        Event::fire(new UserWasCreated($user));

        return ['success' => true];
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
