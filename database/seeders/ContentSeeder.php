<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Post::exists()) {
            return;
        }

        if (User::count() < 10) {
            User::factory()->count(3)->create();
        }

        $categories = Category::factory()->count(4)->create();

        $childCategories = collect();
        foreach (range(1, 4) as $i) {
            $childCategories->push(Category::factory()->create([
                'parent_id' => $categories->random()->id,
            ]));
        }

        $categories = $categories->merge($childCategories);

        $tags = Tag::factory()->count(8)->create();

        $authors = User::inRandomOrder()->take(8)->get();

        foreach (range(1, 12) as $index) {
            $post = Post::factory()->create([
                'category_id' => $categories->random()->id,
                'author_id' => $authors->random()->id,
            ]);

            $post->tags()->sync($tags->random(rand(2, 4))->pluck('id'));

            $this->seedCommentsForPost($post, $authors);
        }
    }

    protected function seedCommentsForPost(Post $post, Collection $authors): void
    {
        $commentTotal = rand(1, 3);

        for ($i = 0; $i < $commentTotal; $i++) {
            $useGuest = (bool) rand(0, 1);

            $commentFactory = Comment::factory()->state([
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
                'is_approved' => (bool) rand(0, 1),
                'is_featured' => (bool) rand(0, 1),
            ]);

            if ($useGuest) {
                $comment = $commentFactory->guest()->create();
            } else {
                $comment = $commentFactory->byUser($authors->random())->create();
            }

            if (! $comment->is_approved || rand(0, 1) === 0) {
                continue;
            }

            $replyTotal = rand(0, 2);

            for ($j = 0; $j < $replyTotal; $j++) {
                Comment::factory()
                    ->byUser($authors->random())
                    ->state([
                        'commentable_type' => Post::class,
                        'commentable_id' => $post->id,
                        'parent_id' => $comment->id,
                        'is_approved' => true,
                        'is_featured' => false,
                    ])->create();
            }
        }
    }
}
