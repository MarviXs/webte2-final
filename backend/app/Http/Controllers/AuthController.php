<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
            'role' => 'user'
        ]);

        $token = Auth::login($user);
        return response()->json([
            'jwt' => $token,
            'message' => 'User Created',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        return response()->json([
            'jwt' => $token,
        ]);
    }

    public function me()
    {
        return Auth::user();
    }

}
