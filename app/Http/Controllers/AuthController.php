<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Event;
use Hash;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Comprueba el usuario y contraseña del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (is_null($user) || ! Hash::check($request->password, $user->password)) {
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
        $request->validate([
            'token' => 'required|max:100',
        ]);

        $user = User::where('remember_token', $request->token)->firstOrFail();
        $user->remember_token = '';
        $user->save();

        return response()->json(['success' => true, 'name' => $user->name, 'token' => $user->api_token]);
    }
}
