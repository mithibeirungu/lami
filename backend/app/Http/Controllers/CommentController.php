<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Car;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Car $car)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $user = $request->user();

        $comment = Comment::create([
            'car_id' => $car->car_id,
            'user_id' => $user->user_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment posted',
            'comment' => $comment->load('user'),
        ], 201);
    }
}
