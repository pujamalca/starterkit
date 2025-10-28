<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_authorized_user_can_create_post_via_api(): void
    {
        $user = $this->makeUserWithManagePosts();
        $token = $user->createToken('test-posts', ['posts:write'])->plainTextToken;
        $category = Category::factory()->create();

        $payload = [
            'title' => 'Judul Post API',
            'slug' => Str::slug('Judul Post API'),
            'content' => 'Konten post melalui API.',
            'category_id' => $category->id,
            'status' => 'draft',
            'type' => 'article',
        ];

        $response = $this->withToken($token)
            ->postJson(route('api.v1.posts.store'), $payload);

        $response->assertCreated()
            ->assertJsonPath('data.title', $payload['title']);

        $this->assertDatabaseHas('posts', [
            'slug' => $payload['slug'],
            'author_id' => $user->id,
        ]);
    }

    public function test_token_without_write_ability_cannot_create_post(): void
    {
        $user = $this->makeUserWithManagePosts();
        $token = $user->createToken('limited', ['posts:read'])->plainTextToken;
        $category = Category::factory()->create();

        $payload = [
            'title' => 'Post Tanpa Izin',
            'slug' => 'post-tanpa-izin',
            'content' => 'Konten',
            'category_id' => $category->id,
            'status' => 'draft',
            'type' => 'article',
        ];

        $response = $this->withToken($token)
            ->postJson(route('api.v1.posts.store'), $payload);

        $response->assertForbidden();

        $this->assertDatabaseMissing('posts', ['slug' => $payload['slug']]);
    }

    public function test_authorized_user_can_update_post_via_api(): void
    {
        $user = $this->makeUserWithManagePosts();
        $token = $user->createToken('update-post', ['posts:write'])->plainTextToken;
        $category = Category::factory()->create();
        $post = Post::factory()
            ->for($user, 'author')
            ->for($category, 'category')
            ->create([
                'status' => 'draft',
            ]);

        $payload = [
            'title' => 'Judul Baru',
            'slug' => 'judul-baru',
            'content' => 'Konten diperbarui',
            'category_id' => $category->id,
            'status' => 'draft',
            'type' => 'article',
        ];

        $response = $this->withToken($token)
            ->patchJson(route('api.v1.posts.update', $post), $payload);

        $response->assertOk()
            ->assertJsonPath('data.slug', $payload['slug']);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $payload['title'],
        ]);
    }

    public function test_authorized_user_can_delete_post_via_api(): void
    {
        $user = $this->makeUserWithManagePosts();
        $token = $user->createToken('delete-post', ['posts:write'])->plainTextToken;
        $post = Post::factory()->for($user, 'author')->create();

        $this->withToken($token)
            ->deleteJson(route('api.v1.posts.destroy', $post))
            ->assertNoContent();

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    protected function makeUserWithManagePosts(): User
    {
        $user = User::factory()->create();

        Permission::query()->firstOrCreate(
            ['name' => 'manage-posts', 'guard_name' => 'web'],
            ['slug' => 'manage-posts', 'module' => 'content'],
        );

        $user->givePermissionTo('manage-posts');

        return $user;
    }
}
