<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BookRepository
{
    /**
     * Optimized catalog listing using cursor pagination.
     * O(1) performance vs OFFSET which degrades to O(n) at 1M records.
     */
    public function getActiveCatalog(int $perPage = 100, array $filters = [])
    {
        $query = Book::select([
                'books.id',
                'books.isbn',
                'books.title',
                'books.author',
                'books.price',
                'books.stock_quantity',
                'books.published_at',
                'books.category_id',
                'books.format',
                'books.is_active',
            ])
            ->with(['category:id,name']) // ✅ only load id and name — no full model
            ->where('is_active', true);

        // Apply optional filters
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        if (!empty($filters['format'])) {
            $query->where('format', $filters['format']);
        }

        return $query
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc') // Secondary sort for stable cursor pagination
            ->cursorPaginate($perPage);
    }

    /**
     * ISBN lookup — hits unique index directly.
     * Cache result to avoid repeated index lookups.
     */
    public function findByIsbn(string $isbn): ?Book
    {
        return Cache::remember(
            "book:isbn:{$isbn}",
            now()->addHours(6),
            fn() => Book::select([
                    'id', 'isbn', 'title', 'author', 'price',
                    'stock_quantity', 'format', 'published_at', 'category_id'
                ])
                ->with(['category:id,name'])
                ->where('isbn', $isbn)
                ->first()
        );
    }

    /**
     * Category filter — uses composite index idx_books_catalog_filter.
     */
    public function getByCategory(int $categoryId, int $perPage = 100)
    {
        $cacheKey = "category:{$categoryId}:books:page";

        return Book::select([
                'id', 'isbn', 'title', 'author',
                'price', 'stock_quantity', 'published_at'
            ])
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->cursorPaginate($perPage);
    }

    /**
     * Full-text search using MySQL FULLTEXT index.
     */
    public function search(string $query, int $perPage = 50)
    {
        return Book::select([
                'id', 'isbn', 'title', 'author',
                'price', 'stock_quantity', 'format'
            ])
            ->whereFullText(['title', 'description'], $query)
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->cursorPaginate($perPage);
    }

    /**
     * Invalidate ISBN cache when a book is updated.
     */
    public function invalidateIsbnCache(string $isbn): void
    {
        Cache::forget("book:isbn:{$isbn}");
    }

    /**
     * Get price range stats — cached aggregate query.
     */
    public function getPriceStats(): array
    {
        return Cache::remember('books:price_stats', now()->addHours(1), function () {
            return DB::table('books')
                ->where('is_active', true)
                ->selectRaw('MIN(price) as min_price, MAX(price) as max_price, AVG(price) as avg_price')
                ->first();
        });
    }
}
