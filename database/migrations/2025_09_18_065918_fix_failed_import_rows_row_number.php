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
        Schema::table('failed_import_rows', function (Blueprint $table) {
            $table->unsignedInteger('row_number')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('failed_import_rows', function (Blueprint $table) {
            $table->unsignedInteger('row_number')->default(null)->change();
        });
    }
};
