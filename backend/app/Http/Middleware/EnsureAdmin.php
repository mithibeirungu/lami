<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role ?? '', ['overseer', 'motor_scribe'])) {
            return response()->json(['message' => 'Forbidden. Admins only.'], 403);
        }

        return $next($request);
    }
}
