<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Car;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // TOGGLE FAVORITE
    public function toggle(Request $request, Car $car)
    {
        $user = $request->user();

        $fav = Favorite::where('user_id', $user->user_id)
                       ->where('car_id', $car->car_id)
                       ->first();

        if ($fav) {
            $fav->delete();
            return response()->json(['message' => 'Removed from favorites']);
        }

        Favorite::create([
            'user_id' => $user->user_id,
            'car_id' => $car->car_id,
        ]);

        return response()->json(['message' => 'Added to favorites']);
    }

    // LIST USER FAVORITES
    public function index(Request $request)
    {
        $user = $request->user();

        return Favorite::with(['car.brand', 'car.bodyType', 'car.images'])
            ->where('user_id', $user->user_id)
            ->get();
    }
}
