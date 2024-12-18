<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class FilmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Создаем 15 жанров
        $genres = Genre::factory()->count(15)->create();

        // Создаем 50 фильмов и связываем их с жанрами
        Film::factory()->count(50)->create()->each(function ($film) use ($genres) {
            // Назначаем случайное количество жанров (от 1 до 3) для каждого фильма
            $film->genres()->attach(
                $genres->random()->id // Получаем случайный жанр
            );
        });
    }
}
