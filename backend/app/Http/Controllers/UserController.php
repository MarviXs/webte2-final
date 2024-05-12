<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List all users
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        return UserResource::collection(User::all());
    }

    /**
     * Create a new user
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

        $createUserData = $request->validate([
            'email' => 'required|string|email|unique:users,email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|min:6',
            'role' => 'required|string|in:user,admin'
        ]);
        $createUserData['password'] = Hash::make($createUserData['password']);

        $user = User::create($createUserData);

        return new UserResource($user);
    }

    /**
     * Get a user
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);
        return new UserResource($user);
    }

    /**
     * Update user details
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $updateUserData = $request->validate([
            'email' => 'string|email|unique:users,email,' . $user->id,
            'first_name' => 'string',
            'last_name' => 'string',
            'password' => 'string|min:6',
            'role' => 'string|in:user,admin'
        ]);

        if (isset($updateUserData['password']) && $updateUserData['password']) {
            $updateUserData['password'] = Hash::make($updateUserData['password']);
        }

        $user->update($updateUserData);

        return new UserResource($user);
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.'], 204);
    }
}
