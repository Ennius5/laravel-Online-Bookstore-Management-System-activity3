<?php

namespace App\Observers;

use App\Models\Book;
use App\Services\BookCacheService;

class BookObserver
{
    public function __construct(
        protected BookCacheService $cacheService
    ) {}

    public function saved(Book $book): void
    {
        $this->cacheService->invalidateCatalog();
        $this->cacheService->invalidateIsbn($book->isbn);
        $this->cacheService->invalidateCategory($book->category_id);
    }

    public function deleted(Book $book): void
    {
        $this->cacheService->invalidateCatalog();
        $this->cacheService->invalidateIsbn($book->isbn);
        $this->cacheService->invalidateCategory($book->category_id);
    }
}
