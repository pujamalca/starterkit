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
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('show_in_header')->default(false)->after('status');
            $table->boolean('show_in_footer')->default(false)->after('show_in_header');
            $table->integer('menu_order')->default(0)->after('show_in_footer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['show_in_header', 'show_in_footer', 'menu_order']);
        });
    }
};
