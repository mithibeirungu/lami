<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'role' => 'nullable|in:driver,motor_scribe,overseer',
        ]);

        // Default to driver role, but allow selection
        $role = $request->role ?? 'driver';

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password_hash' => Hash::make($request->password),
            'role' => $role,
        ]);

        return response()->json([
            'message' => 'User registered',
            'user' => $user
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // create a Sanctum personal access token
        // revoke previous tokens optionally
        try {
            $user->tokens()->delete();
        } catch (\Exception $e) {
            // ignore
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'sanctum_token' => $token,
        ]);
    }
}
