<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Photo>
 */
class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->words(2, true),
            'taken_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'favorited_at' => null,
            'width' => fake()->randomElement([800, 1200, 1600]),
            'height' => fake()->randomElement([600, 900, 1200]),
        ];
    }
}
