<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $this->authorize('view', $user);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'You are not authorized to view this user'], 403);
        }
    
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $this->authorize('update', $user);
            $user->name = $request->input('name') ?? $user->name;
            $user->email = $request->input('email') ?? $user->email;
            $user->password = $request->input('password') ?? $user->password;
            $user->save();
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'You are not authorized to view this user'], 403);
        }
        return new UserResource($user);
    }

}
