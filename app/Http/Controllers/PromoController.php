<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoStoreRequest;
use App\Http\Services\FilmService;
use App\Models\Film;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(PromoStoreRequest $request, Film $film)
    {
        $film->update(['promo' => $request->boolean('promo')]);

        cache()->forget(Film::CACHE_PROMO_KEY);

        return response()->json($film, 201);
    }

    /**
     * Display the specified resource.
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
