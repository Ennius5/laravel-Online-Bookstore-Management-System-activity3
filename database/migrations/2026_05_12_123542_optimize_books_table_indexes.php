<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Composite index for common filtering patterns
            $table->index(
                ['category_id', 'published_at', 'is_active'],
                'idx_books_catalog_filter'
            );

            // Covering index for price range queries
            $table->index(
                ['price', 'stock_quantity', 'id'],
                'idx_books_price_stock'
            );

            // Full-text index for search
            $table->fullText(['title', 'description'], 'idx_books_fulltext');

            // Active book filtering
            $table->index('is_active', 'idx_books_active');

            // Published date for sorting
            $table->index('published_at', 'idx_books_published_at');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('idx_books_catalog_filter');
            $table->dropIndex('idx_books_price_stock');
            $table->dropFullTextIndex(['title', 'description']);
            $table->dropIndex('idx_books_active');
            $table->dropIndex('idx_books_published_at');
        });
    }
};
