<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoStoreRequest;
use App\Http\Services\FilmService;
use App\Models\Film;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/films/{film}/promo",
     *     summary="Set promo status for a film",
     *     tags={"Promo"},
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"promo"},
     *             @OA\Property(property="promo", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Promo status updated successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film not found"
     *     )
     * )
     */
    public function store(PromoStoreRequest $request, Film $film)
    {
        $film->update(['promo' => $request->boolean('promo')]);

        cache()->forget(Film::CACHE_PROMO_KEY);

        return response()->json($film, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/promo",
     *     summary="Get the current promo film",
     *     tags={"Promo"},
     *     @OA\Response(
     *         response=200,
     *         description="Current promo film details",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Promo film not found"
     *     )
     * )
     */
    public function show(FilmService $service)
    {
        $promo = cache()->remember(Film::CACHE_PROMO_KEY, now()->addDay(), fn() => $service->getPromo());

        // Преобразование данных в нужный формат
        $formattedPromo = [
            'id' => $promo->id,
            'name' => $promo->name,
            'posterImage' => $promo->poster_image,
            'previewImage' => $promo->preview_image,
            'backgroundImage' => $promo->background_image,
            'backgroundColor' => $promo->background_color,
            'videoLink' => $promo->video_link,
            'previewVideoLink' => $promo->preview_video_link,
            'description' => $promo->description,
            'rating' => $promo->rating,
            'scoresCount' => $promo->scores_count, // Предполагается, что это поле существует
            'director' => $promo->director,
            'starring' => $promo->starring,
            'runTime' => $promo->run_time,
            'genre' => $promo->genres->map(fn($genre) => $genre->name)->implode(', '), // Используем метод map коллекции
            'released' => $promo->released,
            'isFavorite' => $promo->is_favorite,
        ];

        return response()->json($formattedPromo);
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
 *     @OA\Property (property="runTime", type="string"),
 *     @OA\Property(property="genre", type="string"),
 *     @OA\Property(property="released", type="string"),
 *     @OA\Property(property="isFavorite", type="boolean")
 * )
 */
