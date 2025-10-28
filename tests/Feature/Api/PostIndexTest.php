<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class PostIndexTest extends TestCase
{
    public function test_posts_index_endpoint_responds_successfully(): void
    {
        $response = $this->getJson(route('api.v1.posts.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
            ]);
    }
}
