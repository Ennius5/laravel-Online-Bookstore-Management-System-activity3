<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('publisher')->nullable()->after('author');
            $table->enum('format', ['hardcover', 'paperback', 'ebook', 'audiobook'])
                  ->default('paperback')->after('publisher');
            $table->boolean('is_active')->default(true)->after('format');
            $table->date('published_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['publisher', 'format', 'is_active', 'published_at']);
        });
    }
};
