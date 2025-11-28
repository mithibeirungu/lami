<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $cars = Car::count();
        $users = User::count();
        $comments = Comment::count();
        $favorites = Favorite::count();

        return response()->json([
            'cars' => $cars,
            'users' => $users,
            'comments' => $comments,
            'favorites' => $favorites,
        ]);
    }
}
