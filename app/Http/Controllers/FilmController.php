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
        $films = Film::select(Film::LIST_FIELDS)
            ->when($request->has('genre'), function ($query) use ($request) {
                $query->whereRelation('genres', 'name', $request->get('genre'));
            })
            ->when($request->has('status') && $request->user()?->isModerator(),
                function ($query) use ($request) {
                    $query->whereStatus($request->get('status'));
                },
                function ($query) {
                    $query->whereStatus(Film::STATUS_READY);
                }
            )
            ->ordered($request->get('order_by'), $request->get('order_to'))
            ->paginate(8);

        return response()->json($films);
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
        return response()->json($film->append('rating')->loadCount('scores'));
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
