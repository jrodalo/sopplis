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

class AuthController extends Controller
{

    /**
     * Comprueba el usuario y contraseña del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (is_null($user) || ! Hash::check($request->password, $user->password))
        {
            throw new AuthenticationException();
        }

        return ['success' => true, 'name' => $user->name, 'token' => $user->api_token];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginWithToken(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|max:100',
        ]);

        $user = User::where('remember_token', $request->token)->firstOrFail();
        $user->remember_token = '';
        $user->save();

        return response()->json(['success' => true, 'name' => $user->name, 'token' => $user->api_token]);
    }
}
