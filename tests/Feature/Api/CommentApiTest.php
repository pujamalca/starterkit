<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_approved_comments(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Comment::factory()->count(3)->create([
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
            'is_approved' => true,
        ]);

        Comment::factory()->count(2)->create([
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
            'is_approved' => false,
        ]);

        $response = $this->getJson(route('api.v1.comments.index'));

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_list_comments_for_post(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Comment::factory()->count(3)->create([
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
            'is_approved' => true,
        ]);

        $response = $this->getJson(route('api.v1.posts.comments', $post));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'content', 'created_at'],
                ],
            ]);
    }

    public function test_can_create_comment_on_post(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->postJson(route('api.v1.posts.comments.store', $post), [
            'content' => 'This is a test comment',
        ]);

        $response->assertStatus([201, 202])
            ->assertJsonStructure([
                'data' => ['id', 'content'],
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
    }

    public function test_comment_validation_fails_with_empty_content(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->postJson(route('api.v1.posts.comments.store', $post), [
            'content' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    public function test_authorized_user_can_approve_comment(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $comment = Comment::factory()->create([
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
            'is_approved' => false,
        ]);

        // Create admin user with proper permissions
        $admin = User::factory()->create();
        $admin->givePermissionTo('manage-comments');
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson(route('api.v1.comments.approve', $comment));

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'is_approved' => true,
        ]);
    }

    public function test_authorized_user_can_delete_comment(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $comment = Comment::factory()->create([
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
        ]);

        // Create admin user with proper permissions
        $admin = User::factory()->create();
        $admin->givePermissionTo('manage-comments');
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson(route('api.v1.comments.destroy', $comment));

        $response->assertStatus(204);

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
        ]);
    }
}
