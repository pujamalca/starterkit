<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->string('guard_name')->default('web');
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false)->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

