<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);
        $seoTitle = fake()->optional()->words(6, true);
        $seoDescription = fake()->optional()->sentences(3, true);

        return [
            'category_id' => Category::factory(),
            'author_id' => User::factory(),
            'title' => $title,
            'excerpt' => fake()->optional()->paragraph(),
            'content' => fake()->paragraphs(6, true),
            'featured_image' => null,
            'gallery' => fake()->optional()->randomElements([
                fake()->imageUrl(800, 600, 'nature'),
                fake()->imageUrl(800, 600, 'city'),
                fake()->imageUrl(800, 600, 'tech'),
            ], fake()->numberBetween(0, 3)),
            'type' => fake()->randomElement(['article', 'page', 'news']),
            'status' => fake()->randomElement(['draft', 'published']),
            'published_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'scheduled_at' => null,
            'is_featured' => fake()->boolean(10),
            'is_sticky' => fake()->boolean(5),
            'view_count' => fake()->numberBetween(0, 5000),
            'reading_time' => fake()->numberBetween(2, 10),
            'seo_title' => $seoTitle ? Str::limit($seoTitle, 60, '') : null,
            'seo_description' => $seoDescription ? Str::limit($seoDescription, 160, '') : null,
            'seo_keywords' => fake()->optional()->words(5, true),
            'og_image' => fake()->optional()->imageUrl(1200, 630, 'business'),
            'metadata' => [
                'source' => fake()->optional()->company(),
            ],
        ];
    }
}
