<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
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

        return response()->json(
            $formattedFilms,

        );
    }

    /**
     * Store a newly created resource in storage.
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
