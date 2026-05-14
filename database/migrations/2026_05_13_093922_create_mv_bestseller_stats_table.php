<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create the materialized view table
        Schema::create('mv_bestseller_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->integer('total_books')->default(0);
            $table->decimal('avg_price', 8, 2)->default(0);
            $table->bigInteger('total_inventory')->default(0);
            $table->integer('bestseller_count')->default(0);
            $table->date('latest_publication')->nullable();
            $table->timestamp('last_refreshed_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index('category_id');
        });

        // Populate immediately on migration
        $this->refresh();
    }

    public function down(): void
    {
        Schema::dropIfExists('mv_bestseller_stats');
    }

    private function refresh(): void
    {
        DB::statement("
            INSERT INTO mv_bestseller_stats
                (category_id, total_books, avg_price, total_inventory, bestseller_count, latest_publication, last_refreshed_at, created_at, updated_at)
            SELECT
                category_id,
                COUNT(*) as total_books,
                ROUND(AVG(price), 2) as avg_price,
                SUM(stock_quantity) as total_inventory,
                COUNT(CASE WHEN stock_quantity > 500 THEN 1 END) as bestseller_count,
                MAX(published_at) as latest_publication,
                NOW() as last_refreshed_at,
                NOW() as created_at,
                NOW() as updated_at
            FROM books
            WHERE is_active = 1
            GROUP BY category_id
        ");
    }
};
