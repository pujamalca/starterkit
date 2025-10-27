<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $word = fake()->unique()->word();

        return [
            'name' => ucfirst($word),
            'type' => 'post',
            'color' => fake()->optional()->hexColor(),
            'description' => fake()->optional()->sentence(),
            'metadata' => null,
        ];
    }
}

