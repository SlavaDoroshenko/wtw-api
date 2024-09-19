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

        return response()->json($promo);
    }
}
