<?php

namespace Tests\Feature\Api;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_published_pages(): void
    {
        $author = User::factory()->create();

        Page::factory()->count(3)->create([
            'author_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Page::factory()->count(2)->create([
            'author_id' => $author->id,
            'status' => 'draft',
        ]);

        $response = $this->getJson(route('api.v1.pages.index'));

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug', 'content'],
                ],
            ]);
    }

    public function test_can_search_pages(): void
    {
        $author = User::factory()->create();

        Page::factory()->create([
            'author_id' => $author->id,
            'title' => 'About Us',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Page::factory()->create([
            'author_id' => $author->id,
            'title' => 'Contact Page',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson(route('api.v1.pages.index', ['search' => 'About']));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_show_published_page(): void
    {
        $author = User::factory()->create();

        $page = Page::factory()->create([
            'author_id' => $author->id,
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson(route('api.v1.pages.show', $page->slug));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'slug', 'content'],
            ])
            ->assertJson([
                'data' => [
                    'slug' => 'privacy-policy',
                ],
            ]);
    }

    public function test_cannot_show_draft_page(): void
    {
        $author = User::factory()->create();

        $page = Page::factory()->create([
            'author_id' => $author->id,
            'slug' => 'draft-page',
            'status' => 'draft',
        ]);

        $response = $this->getJson(route('api.v1.pages.show', $page->slug));

        $response->assertStatus(404);
    }

    public function test_page_not_found_returns_404(): void
    {
        $response = $this->getJson(route('api.v1.pages.show', 'non-existent-page'));

        $response->assertStatus(404);
    }
}
