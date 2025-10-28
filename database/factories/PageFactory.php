<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'author_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'content' => fake()->paragraphs(6, true),
            'status' => fake()->randomElement(['draft', 'published']),
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'seo_title' => $title,
            'seo_description' => fake()->sentence(12),
            'canonical_url' => null,
            'og_image' => null,
            'metadata' => [],
        ];
    }
}

