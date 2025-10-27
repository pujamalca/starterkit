<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'description' => fake()->optional()->paragraph(),
            'image' => fake()->optional()->imageUrl(),
            'icon' => fake()->optional()->lexify('icon-????'),
            'color' => fake()->optional()->hexColor(),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_featured' => fake()->boolean(20),
            'is_active' => true,
            'metadata' => [
                'seo' => [
                    'title' => ucfirst($name),
                    'description' => fake()->sentence(),
                ],
            ],
        ];
    }
}

