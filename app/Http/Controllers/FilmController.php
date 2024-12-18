<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFilmRequest;
use App\Http\Services\FilmService;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $films = Film::with('genres')->get();

        // Преобразование данных в нужный формат
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
    public function store(AddFilmRequest $request)
    {
        Film::create([
            'imdb_id' => $request->input('imdb'),
            'status' => Film::STATUS_PENDING,
        ]);

        return response()->json(['message' => 'Film added to moderation'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Film $film)
    {
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
        return response()->json($formattedFilm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Film $film)
    {
        $film->update($request->validated());

        return response()->json(['message' => 'Film updated'], 201);
    }

    /**
     * Remove the specified resource f
     * rom storage.
     */
    public function similar(Film $film, FilmService $service)
    {
        return response()->json($service->getSimilarFor($film, Film::LIST_FIELDS));
    }
}
