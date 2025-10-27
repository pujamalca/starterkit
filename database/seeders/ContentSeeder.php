<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $additionalUsers = User::factory()->count(3)->create();
        $allUsers = User::query()->get();

        $categories = Category::factory()->count(4)->create();

        Category::factory()
            ->count(4)
            ->create(fn () => ['parent_id' => $categories->random()->id]);

        $tags = Tag::factory()->count(8)->create();

        $posts = Post::factory()
            ->count(12)
            ->make()
            ->each(function (Post $post) use ($categories, $allUsers) {
                $post->category_id = $categories->random()->id;
                $post->author_id = $allUsers->random()->id;

                if ($post->status === 'published' && ! $post->published_at) {
                    $post->published_at = now()->subDays(rand(1, 14));
                }

                $post->save();
            });

        $posts->each(function (Post $post) use ($tags, $allUsers) {
            $post->tags()->sync($tags->random(rand(2, 4))->pluck('id'));

            Comment::factory()
                ->count(rand(1, 3))
                ->make()
                ->each(function (Comment $comment) use ($post, $allUsers) {
                    $comment->commentable_type = Post::class;
                    $comment->commentable_id = $post->id;
                    $comment->user_id = Arr::random([$allUsers->random()->id, null]);

                    if (! $comment->user_id) {
                        $comment->guest_name = fake()->name();
                        $comment->guest_email = fake()->safeEmail();
                    }

                    $comment->save();

                    if (rand(0, 1)) {
                        Comment::factory()
                            ->count(rand(0, 2))
                            ->make()
                            ->each(function (Comment $reply) use ($comment, $allUsers) {
                                $reply->commentable_type = $comment->commentable_type;
                                $reply->commentable_id = $comment->commentable_id;
                                $reply->parent_id = $comment->id;
                                $reply->user_id = $allUsers->random()->id;
                                $reply->is_approved = true;
                                $reply->save();
                            });
                    }
                });
        });
    }
}

