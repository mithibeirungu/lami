<?php

namespace App\Http\Controllers;

use App\Models\AdminInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminInvitationController extends Controller
{
    // Create a new invite (admin only)
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'expires_in_minutes' => 'nullable|integer|min:1',
        ]);

        $expiresAt = null;
        if ($request->filled('expires_in_minutes')) {
            $expiresAt = Carbon::now()->addMinutes((int) $request->expires_in_minutes);
        }

        $invite = AdminInvitation::generate($request->email, $expiresAt);

        return response()->json([
            'message' => 'Invitation created',
            'invite' => $invite,
        ]);
    }

    // Optionally list invites (admin only)
    public function index()
    {
        $invites = AdminInvitation::orderBy('created_at', 'desc')->paginate(50);
        return response()->json($invites);
    }

    // Generate an invite code for the currently authenticated user and log it to the server
    public function requestForUser(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'expires_in_minutes' => 'nullable|integer|min:1',
        ]);

        $expiresAt = null;
        if ($request->filled('expires_in_minutes')) {
            $expiresAt = Carbon::now()->addMinutes((int) $request->expires_in_minutes);
        }

        $invite = AdminInvitation::generate($user->email, $expiresAt);

        // Log to Laravel log and stdout so it's visible in terminal when running the server
        Log::info('Admin invite generated for user '.$user->email.': '.$invite->code);
        // also echo to stdout (helpful when using `php artisan serve`)
        if (app()->runningInConsole()) {
            echo "Admin invite for {$user->email}: {$invite->code}\n";
        }

        return response()->json([
            'message' => 'Invite created. Check server logs/terminal for the code.'
        ]);
    }
}
