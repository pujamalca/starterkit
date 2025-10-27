<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('collection_name')->default('default');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type', 100)->nullable();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('conversions_disk')->nullable();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->json('custom_properties')->nullable();
            $table->json('responsive_images')->nullable();
            $table->json('manipulations')->nullable();
            $table->json('generated_conversions')->nullable();
            $table->integer('order_column')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('order_column');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

