<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // return $request ;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'User Created Successfuly',
            'data' => [
                'user' => UserResource::make($user)
            ],
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        // return $request;
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Incorrect !'
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        return response()->json([
            'status' => 'Success',
            'message' => 'Logged In Successfully',
            'data' => [
                'user' => UserResource::make($user),
                'token' => $token
            ]
        ], 200);
    }

    public function profile()
    {
        return response()->json([
            'status' => 'Success',
            'message' => 'Logged In Successfully',
            'data' => [
                'user' => UserResource::make(Auth::user()),
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Logged Out Successfully',

        ], 200);
    }
}
