<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence($this->faker->numberBetween(5, 8));

        $slugBase = Str::slug($title);
        $slug = $slugBase . '-' . $this->faker->unique()->numberBetween(1000, 999999);
     
        return [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $this->faker->paragraph($this->faker->numberBetween(2, 4)),
            'body' => collect(range(1, $this->faker->numberBetween(2, 4)))
                            ->map(fn () => $this->faker->paragraph($this->faker->numberBetween(3, 6)))
                            ->implode('\n\n'),
            'is_published' => $this->faker->boolean(70),
            'published_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'user_id' => null
        ];
    }
}