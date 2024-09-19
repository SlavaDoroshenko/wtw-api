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
        $films = Auth::user()->films()->get(Film::LIST_FIELDS)->toArray();

        return response()->json($films);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Film $film)
    {
        $user = Auth::user();
        if ($user->hasFilm($film)) {
          return response()->json('Такой фильм уже находиться в избранном', 403);
        }

        $user->films()->attach($film);

        return response()->json($film, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Film $film)
    {
        $user = Auth::user();
        if (!$user->hasFilm($film)) {
            return response()->json('Такого фильма итак нет в избранном', 403);
        }

        $user->films()->detach($film);

        return response()->json($film, 201);
    }
}
