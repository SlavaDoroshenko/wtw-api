<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'poster_image' => $this->faker->imageUrl(),
            'preview_image' => $this->faker->imageUrl(),
            'background_image' => $this->faker->imageUrl(),
            'background_color' => $this->faker->hexColor(),
            'video_link' => $this->faker->url(),
            'preview_video_link' => $this->faker->url(),
            'description' => $this->faker->paragraph(),
            'director' => $this->faker->name(),
            'starring' => json_encode($this->faker->words(5)),
            'run_time' => $this->faker->numberBetween(80, 180),
            'released' => $this->faker->year(),
            'promo' => $this->faker->boolean(),
            'status' => 'pending',
            'imdb_id' => 'tt' . $this->faker->randomNumber(7, true),
        ];
    }
}
