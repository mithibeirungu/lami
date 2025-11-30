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

    // REGISTER ADMIN (via invite code)
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required',
            'password' => 'required|min:6',
            'invite_code' => 'nullable',
        ]);

        // If there are no admins yet, allow first admin to be created without an invite
        $anyAdmin = User::where('type_of_user', 'admin')->exists();
        if (!$anyAdmin) {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'full_name' => $request->full_name,
                'password' => bcrypt($request->password),
                'type_of_user' => 'admin',
            ]);

            return response()->json([
                'message' => 'First admin created (bootstrap)',
                'user' => $user
            ]);
        }

        // locate invitation
        $invite = \App\Models\AdminInvitation::where('code', $request->invite_code)->first();
        if (!$invite) {
            return response()->json(['message' => 'Invalid invite code'], 403);
        }

        if (!$invite->isUsable()) {
            return response()->json(['message' => 'Invite code expired or already used'], 403);
        }

        // optional: if invite has an email set, ensure it matches
        if ($invite->email && strtolower($invite->email) !== strtolower($request->email)) {
            return response()->json(['message' => 'Invite code not valid for this email'], 403);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password' => bcrypt($request->password),
            'type_of_user' => 'admin',
        ]);

        // mark invite used
        $invite->used_at = now();
        $invite->save();

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
