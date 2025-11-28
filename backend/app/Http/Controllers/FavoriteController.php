<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // TOGGLE FAVORITE
    public function toggle(Request $request, $carId)
    {
        $user = $request->user();
        $userId = $user ? $user->id : $request->input('user_id');

        $fav = Favorite::where('user_id', $userId)
                       ->where('post_id', $carId)
                       ->first();

        if ($fav) {
            $fav->delete();
            return response()->json(['message' => 'Removed from favorites']);
        }

        Favorite::create([
            'user_id' => $userId,
            'post_id' => $carId,
        ]);

        return response()->json(['message' => 'Added to favorites']);
    }

    // LIST USER FAVORITES
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = $user ? $user->id : $request->input('user_id');

        return Favorite::with('car')
            ->where('user_id', $userId)
            ->get();
    }
}
