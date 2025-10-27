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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general');
            $table->string('name', 100);
            $table->string('display_name', 200)->nullable();
            $table->text('payload')->nullable();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('text');
            $table->json('details')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_autoload')->default(false);
            $table->boolean('locked')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['group', 'name']);
            $table->index('group');
            $table->index('is_autoload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
