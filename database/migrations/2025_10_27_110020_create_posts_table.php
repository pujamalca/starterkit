<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('author_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->enum('type', ['article', 'page', 'news'])->default('article');
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_sticky')->default(false);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedInteger('reading_time')->nullable();
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_description', 160)->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('author_id');
            $table->index('type');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_sticky');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

