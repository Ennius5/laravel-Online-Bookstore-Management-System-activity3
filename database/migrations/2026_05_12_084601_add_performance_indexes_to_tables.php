<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Books table indexes
        Schema::table('books', function (Blueprint $table) {
            $table->index('category_id', 'idx_books_category_id');
            $table->index('price', 'idx_books_price');
            $table->index('created_at', 'idx_books_created_at');
            // isbn is likely already unique, but add index if not
            if (!$this->hasIndex('books', 'idx_books_isbn')) {
                $table->index('isbn', 'idx_books_isbn');
            }
        });

        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id', 'idx_orders_user_id');
            $table->index('status', 'idx_orders_status');
            $table->index('created_at', 'idx_orders_created_at');
        });

        // Order items table indexes
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id', 'idx_order_items_order_id');
            $table->index('book_id', 'idx_order_items_book_id');
        });

        // Reviews table indexes
        Schema::table('reviews', function (Blueprint $table) {
            $table->index('book_id', 'idx_reviews_book_id');
            $table->index('user_id', 'idx_reviews_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('idx_books_category_id');
            $table->dropIndex('idx_books_price');
            $table->dropIndex('idx_books_created_at');
            $table->dropIndex('idx_books_isbn');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_user_id');
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_created_at');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('idx_order_items_order_id');
            $table->dropIndex('idx_order_items_book_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('idx_reviews_book_id');
            $table->dropIndex('idx_reviews_user_id');
        });
    }

    private function hasIndex(string $table, string $index): bool
    {
        return collect(\Illuminate\Support\Facades\DB::select(
            "SHOW INDEX FROM {$table} WHERE Key_name = ?", [$index]
        ))->isNotEmpty();
    }
};
