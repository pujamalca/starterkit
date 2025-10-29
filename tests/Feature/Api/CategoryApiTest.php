<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        Category::factory()->count(5)->create(['is_active' => true]);

        $response = $this->getJson(route('api.v1.categories.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'description'],
                ],
            ]);
    }

    public function test_can_filter_categories_by_search(): void
    {
        Category::factory()->create(['name' => 'Technology', 'is_active' => true]);
        Category::factory()->create(['name' => 'Sports', 'is_active' => true]);

        $response = $this->getJson(route('api.v1.categories.index', ['search' => 'Tech']));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_show_single_category(): void
    {
        $category = Category::factory()->create([
            'name' => 'Technology',
            'slug' => 'technology',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.v1.categories.show', $category));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'slug'],
            ])
            ->assertJson([
                'data' => [
                    'slug' => 'technology',
                ],
            ]);
    }

    public function test_can_list_posts_by_category(): void
    {
        $author = User::factory()->create();
        $category = Category::factory()->create(['is_active' => true]);

        Post::factory()->count(3)->create([
            'category_id' => $category->id,
            'author_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson(route('api.v1.categories.posts', $category));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug'],
                ],
            ]);
    }

    public function test_category_not_found_returns_404(): void
    {
        $response = $this->getJson(route('api.v1.categories.show', 'non-existent-slug'));

        $response->assertStatus(404);
    }
}
