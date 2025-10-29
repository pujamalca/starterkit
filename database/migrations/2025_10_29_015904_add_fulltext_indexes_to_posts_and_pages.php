<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add fulltext index to posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->fullText(['title', 'content', 'excerpt'], 'posts_fulltext_index');
        });

        // Add fulltext index to pages table
        Schema::table('pages', function (Blueprint $table) {
            $table->fullText(['title', 'content'], 'pages_fulltext_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropFullText('posts_fulltext_index');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropFullText('pages_fulltext_index');
        });
    }
};
