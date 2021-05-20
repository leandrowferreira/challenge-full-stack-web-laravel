<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Attemps to login using credentials against database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            //401: Unauthorized
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        //200: OK
        return response()->json([
            'access_token' => $request->user()->createToken('auth_token')->plainTextToken,
            'token_type'   => 'Bearer',
        ], 200);
    }

    /**
     * Attemps to log out the current user, deleting their tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        //The user needs to be logged it to be logged out
        if (auth('sanctum')->user()) {
            auth('sanctum')->user()->tokens()->delete();
        }

        //205: Reset Content
        return response('', 205);
    }

    /**
     * Returns info about logged user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        //200: OK
        return response(json_encode(new UserResource($request->user())), 200);
    }
}
