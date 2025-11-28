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
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password' => bcrypt($request->password),
            'type_of_user' => 'user', // default
        ]);

        return response()->json([
            'message' => 'User registered',
            'user' => $user
        ]);
    }

    // REGISTER ADMIN (requires ADMIN_SECRET in .env)
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required',
            'password' => 'required|min:6',
            'admin_secret' => 'required',
        ]);

        if ($request->admin_secret !== env('ADMIN_SECRET')) {
            return response()->json(['message' => 'Invalid admin secret'], 403);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password' => bcrypt($request->password),
            'type_of_user' => 'admin',
        ]);

        return response()->json([
            'message' => 'Admin registered',
            'user' => $user
        ]);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
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
