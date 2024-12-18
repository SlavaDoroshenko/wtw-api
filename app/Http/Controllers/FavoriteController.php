<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Get favorite films for the authenticated user",
     *     tags={"Favorites"},
     *     @OA\Response(
     *         response=200,
     *         description="List of favorite films",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
        $films = Auth::user()->films()->with('genres')->get();

        $formattedFilms = $films->map(function ($film) {
            return [
                'id' => $film->id,
                'name' => $film->name,
                'posterImage' => $film->poster_image,
                'previewImage' => $film->preview_image,
                'backgroundImage' => $film->background_image,
                'backgroundColor' => $film->background_color,
                'videoLink' => $film->video_link,
                'previewVideoLink' => $film->preview_video_link,
                'description' => $film->description,
                'rating' => $film->rating,
                'scoresCount' => $film->scores_count,
                'director' => $film->director,
                'starring' => $film->starring,
                'runTime' => $film->run_time,
                'genre' => $film->genres->first()->name ?? null,
                'released' => $film->released,
                'isFavorite' => $film->is_favorite,
            ];
        });

        return response()->json($formattedFilms);
    }

    /**
     * @OA\Patch(
     *     path="/api/favorites/{filmId}/{status}",
     *     summary="Update favorite status of a film",
     *     tags={"Favorites"},
     *     @OA\Parameter(
     *         name="filmId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", enum={0, 1})
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Film not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function updateFavoriteStatus($filmId, $status)
    {
        $user = Auth::user();
        $film = Film::find($filmId);

        if (!$film) {
            return response()->json('Фильм не найден', 404);
        }

        // Приводим статус к булевому значению
        $isFavorite = $status == 1;

        if ($isFavorite) {
            // Если статус 1, добавляем фильм в избранное
            if ($user->hasFilm($film)) {
                return response()->json('Такой фильм уже находится в избранном', 403);
            }
            $user->films()->attach($film);
        } else {
            // Если статус 0, удаляем фильм из избранного
            if (!$user->hasFilm($film)) {
                return response()->json('Такого фильма итак нет в избранном', 403);
            }
            $user->films()->detach($film);
        }

        // Возвращаем обновленный фильм
        $film->is_favorite = $isFavorite; // Обновляем поле isFavorite
        $formattedFilm = [
            'id' => $film->id,
            'name' => $film->name,
            'posterImage' => $film->poster_image,
            'previewImage' => $film->preview_image,
            'backgroundImage' => $film->background_image,
            'backgroundColor' => $film->background_color,
            'videoLink' => $film->video_link,
            'previewVideoLink' => $film->preview_video_link,
            'description' => $film->description,
            'rating' => $film->rating,
            'scoresCount' => $film->scores_count,
            'director' => $film->director,
            'starring' => $film->starring,
            'runTime' => $film->run_time,
            'genre' => $film->genres->first()->name ?? null,
            'released' => $film->released,
            'isFavorite' => $film->is_favorite,
        ];
        return response()->json($formattedFilm, 200);
    }
}

/**
 * @OA\Schema(
 *     schema="Film",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="posterImage", type="string"),
 *     @OA\Property(property="previewImage", type="string"),
 *     @OA\Property(property="backgroundImage", type="string"),
 *     @OA\Property(property="backgroundColor", type="string"),
 *     @OA\Property(property="videoLink", type="string"),
 *     @OA\Property(property="previewVideoLink", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="rating", type="number", format="float"),
 *     @OA\Property(property="scoresCount", type="integer"),
 *     @OA\Property(property="director", type="string"),
 *     @OA\Property(property="starring", type="string"),
 *     @OA\Property(property="runTime", type="integer"),
 *     @OA\Property(property="genre", type="string"),
 *     @OA\Property(property="released", type="string", format="date"),
 *     @OA\Property(property="isFavorite", type="boolean")
 * )
 */
