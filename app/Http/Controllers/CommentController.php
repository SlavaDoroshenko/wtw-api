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
     * @OA\Get(
     *     path="/api/films/{film}/comments",
     *     summary="Get comments for a film",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of comments",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film not found"
     *     )
     * )
     */
    public function index(Film $film)
    {
        return response()->json($film->comments()->get());
    }

    /**
     * @OA\Post(
     *     path="/api/films/{film}/comments",
     *     summary="Add a new comment to a film",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rating", "comment"},
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Great movie!"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/comments/{comment}",
     *     summary="Update a comment",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rating", "comment"},
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Updated comment text"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return response()->json(['comment' => $comment], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{comment}",
     *     summary="Delete a comment",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
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
