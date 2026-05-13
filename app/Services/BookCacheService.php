<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BookCacheService
{
    const CATALOG_TTL  = 300;   // 5 minutes
    const ISBN_TTL     = 21600; // 6 hours
    const CATEGORY_TTL = 3600;  // 1 hour
    const STATS_TTL    = 3600;  // 1 hour

    public function getCatalogStats(): array
    {
        return Cache::tags(['books', 'stats'])
            ->remember('books:catalog_stats', self::STATS_TTL, function () {
                return [
                    'total'        => DB::table('books')->count(),
                    'active'       => DB::table('books')->where('is_active', true)->count(),
                    'out_of_stock' => DB::table('books')->where('stock_quantity', 0)->count(),
                    'categories'   => DB::table('books')->distinct()->count('category_id'),
                ];
            });
    }

    public function getCategoryBooks(int $categoryId): mixed
    {
        return Cache::tags(['books', "category:{$categoryId}"])
            ->remember(
                "category:{$categoryId}:popular",
                self::CATEGORY_TTL,
                fn() => Book::select(['id', 'title', 'author', 'price', 'stock_quantity'])
                    ->where('category_id', $categoryId)
                    ->where('is_active', true)
                    ->orderBy('published_at', 'desc')
                    ->limit(1000)
                    ->get()
            );
    }

    public function invalidateCatalog(): void
    {
        // ✅ Flush only book-related caches, not everything
        Cache::tags(['books'])->flush();
    }

    public function invalidateCategory(int $categoryId): void
    {
        Cache::tags(["category:{$categoryId}"])->flush();
    }

    public function invalidateIsbn(string $isbn): void
    {
        Cache::forget("book:isbn:{$isbn}");
    }
}
