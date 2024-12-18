<?php

namespace Database\Factories;

use App\Models\Film;
use App\Models\Genre; // Импортируем модель Genre
use Illuminate\Database\Eloquent\Factories\Factory;

class FilmFactory extends Factory
{
    protected $model = Film::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'poster_image' => $this->faker->imageUrl(640, 480, 'film', true), // URL для постера
            'preview_image' => $this->faker->imageUrl(640, 480, 'film', true), // URL для превью
            'background_image' => $this->faker->imageUrl(1280, 720, 'film', true), // URL для фона
            'background_color' => $this->faker->hexColor(), // Случайный цвет фона
            'video_link' => $this->faker->url(), // Случайная ссылка на видео
            'preview_video_link' => $this->faker->url(), // Случайная ссылка на превью видео
            'description' => $this->faker->paragraph(2), // Описание фильма
            'director' => $this->faker->name(), // Режиссер
            'starring' => [$this->faker->name(), $this->faker->name(), $this->faker->name()], // Актеры
            'run_time' => random_int(60, 240), // Время в минутах
            'released' => $this->faker->year(), // Год выпуска
            'promo' => $this->faker->boolean(), // Промо-статус
            'status' => Film::STATUS_READY, // Статус
            'imdb_id' => 'tt00' . random_int(1, 9999), // Случайный IMDB ID
            'rating' => $this->faker->randomFloat(1, 1, 10), // Случайный рейтинг
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Film::STATUS_PENDING,
            ];
        });
    }
}
