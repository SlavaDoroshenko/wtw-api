<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Film $film)
    {
        return response()->json($film->comments()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddCommentRequest $request, Film $film)
    {
        $comment = $film->comments()->create([
            'rating' => $request->rating,
            'text' => $request->comment,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['comment' => $comment], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return response()->json(['comment' => $comment], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (($comment->user_id !== Auth::id()) && !Auth::user()->isModerator()) {
            return response()->json(['message' => 'Forbidden'], 403);
        } else {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted'], 200);
        }


    }
}
