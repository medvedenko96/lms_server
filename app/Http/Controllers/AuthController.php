<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request) {
        if(!Auth::attempt($request->only('email', 'password'))) {
            return \response(['error' => 'Invalid Credentials!'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('token', $token, 60*24);

        return \response(['jwt' => $token])->withCookie($cookie);
    }

    public function user(Request $request) {
        return $request->user();
    }

    public function logout () {
        $cookie = Cookie::forget('token');

        return \response(['message' => 'success'])->withCookie($cookie);
    }
}
