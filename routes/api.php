<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::delete('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('auth.logout');

Route::controller(UserController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/login', 'show')->name('user.show');
        Route::patch('/user', 'update')->name('user.update');
    });

Route::get('/films/{film}/similar', [FilmController::class, 'similar'])->name('films.similar');

Route::controller(FilmController::class)
    ->group(function () {
        Route::get('/films', 'index')->name('films.index');
        Route::post('/films', 'store')->middleware(['auth:sanctum', 'isModerator'])->name('films.store');
        Route::get('/films/{film}', 'show')->name('films.show');
        Route::patch('/films/{film}', 'update')->middleware(['auth:sanctum', 'isModerator'])->name('films.update');
    });

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::patch('/genres/{genre}', [GenreController::class, 'update'])->middleware(['auth:sanctum', 'isModerator'])->name('genres.update');

Route::controller(FavoriteController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/favorite', 'index')->name('favorite.index');
        Route::post('/favorite/{filmId}/{status}', 'updateFavoriteStatus')->name('favorite.update');
    });

Route::get('comments/{film}', [CommentController::class, 'index'])->name('comments.index');
Route::controller(CommentController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('comments/{film}', 'store')->name('comments.store');
        Route::patch('/comments/{comment}', 'update')->name('comments.update');
        Route::delete('/comments/{comment}', 'destroy')->name('comments.destroy');
    });

Route::get('/promo', [PromoController::class, 'show'])->name('promo.show');
Route::post('/promo/{film}', [PromoController::class, 'store'])->middleware(['auth:sanctum', 'isModerator'])->name('promo.store');

Route::controller(RoomController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/rooms', 'index')->name('rooms.index');
    Route::post('/rooms', 'store')->name('rooms.store');
    Route::patch('/rooms/{room}', 'update')->name('rooms.update');
    Route::delete('/rooms/{room}', 'destroy')->name('rooms.destroy');


    Route::post('/rooms/{room}/join', 'joinToRoom')->name('rooms.joinToRoom');
});
