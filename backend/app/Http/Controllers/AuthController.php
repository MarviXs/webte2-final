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
                'message' => 'Unauthorized',
            ], 401);
        }
        return response()->json([
            'jwt' => $token,
        ]);
    }

    public function me(): User
    {
        return Auth::user();
    }

    public function change_password(Request $request)
    {
        // Validate the request data
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password does not match',
            ], 400);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }

}
