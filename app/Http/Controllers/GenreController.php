<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGenreRequest;
use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Genre::all();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        $genre->update($request->validated());
        return response()->json($genre);
    }

}
