<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('module', 50)->nullable()->index();
            $table->string('guard_name')->default('web');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

