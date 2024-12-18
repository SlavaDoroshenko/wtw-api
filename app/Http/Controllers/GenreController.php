<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGenreRequest;
use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/genres",
     *     summary="Get a list of genres",
     *     tags={"Genres"},
     *     @OA\Response(
     *         response=200,
     *         description="List of genres",
     *     )
     * )
     */
    public function index()
    {
        return Genre::all();
    }

    /**
     * @OA\Put(
     *     path="/api/genres/{genre}",
     *     summary="Update a specific genre",
     *     tags={"Genres"},
     *     @OA\Parameter(
     *         name="genre",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Action")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Genre updated successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Genre not found"
     *     )
     * )
     */
    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        $genre->update($request->validated());
        return response()->json($genre);
    }
}

/**
 * @OA\Schema(
 *     schema="Genre",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string")
 * )
 */
