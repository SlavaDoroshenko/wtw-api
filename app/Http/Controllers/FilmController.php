<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFilmRequest;
use App\Http\Services\FilmService;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/films",
     *     summary="Get a list of films",
     *     tags={"Films"},
     *     @OA\Response(
     *         response=200,
     *         description="List of films",
     *     )
     * )
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

        return response()->json($formattedFilms);
    }

    /**
     * @OA\Post(
     *     path="/api/films",
     *     summary="Add a new film",
     *     tags={"Films"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"imdb"},
     *             @OA\Property(property="imdb", type="string", example="tt1234567")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Film added to moderation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Film added to moderation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/films/{film}",
     *     summary="Get a specific film by ID",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Film details",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film not found"
     *     )
     * )
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
            'scoresCount' => $film-> scores_count,
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
     * @OA\Put(
     *     path="/api/films/{film}",
     *     summary="Update a specific film",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Film updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Film updated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film not found"
     *     )
     * )
     */
    public function update(Request $request, Film $film)
    {
        $film->update($request->validated());

        return response()->json(['message' => 'Film updated'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/films/{film}/similar",
     *     summary="Get similar films",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of similar films",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film not found"
     *     )
     * )
     */
    public function similar(Film $film, FilmService $service)
    {
        return response()->json($service->getSimilarFor($film, Film::LIST_FIELDS));
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
