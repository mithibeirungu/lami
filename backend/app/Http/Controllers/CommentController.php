<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $carId)
    {
        $request->validate([
            'comment_text' => 'required',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $user = $request->user();
        $userId = $user ? $user->id : $request->input('user_id');

        $comment = Comment::create([
            'post_id' => $carId,
            'user_id' => $userId,
            'comment_text' => $request->comment_text,
            'rating' => $request->rating,
        ]);

        return response()->json($comment);
    }
}
