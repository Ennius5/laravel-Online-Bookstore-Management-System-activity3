<?php

namespace App\Exports;

use App\Models\Book;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class BooksExport implements FromQuery, WithHeadings, WithMapping, ShouldQueue
{
    use Exportable;

    // All available columns
    const AVAILABLE_COLUMNS = [
        'isbn'           => 'ISBN',
        'title'          => 'Title',
        'author'         => 'Author',
        'price'          => 'Price',
        'stock_quantity' => 'Stock',
        'category'       => 'Category',
        'description'    => 'Description',
        'created_at'     => 'Date Added',
    ];

    protected array $filters;
    protected array $selectedColumns;

    public function __construct(array $filters = [], array $selectedColumns = [])
    {
        $this->filters         = $filters;
        $this->selectedColumns = empty($selectedColumns)
            ? array_keys(self::AVAILABLE_COLUMNS) // default: all columns
            : $selectedColumns;
    }

    public function query()
    {
        $query = Book::query()->with('category');

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }
        if (!empty($this->filters['min_price'])) {
            $query->where('price', '>=', $this->filters['min_price']);
        }
        if (!empty($this->filters['max_price'])) {
            $query->where('price', '<=', $this->filters['max_price']);
        }
        if (!empty($this->filters['in_stock'])) {
            $query->where('stock_quantity', '>', 0);
        }
        // ✅ Date range filter
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query;
    }

    public function headings(): array
    {
        return collect($this->selectedColumns)
            ->map(fn($col) => self::AVAILABLE_COLUMNS[$col] ?? $col)
            ->values()
            ->toArray();
    }

    public function map($book): array
    {
        $map = [
            'isbn'           => $book->isbn,
            'title'          => $book->title,
            'author'         => $book->author,
            'price'          => $book->price,
            'stock_quantity' => $book->stock_quantity,
            'category'       => $book->category->name ?? 'N/A',
            'description'    => $book->description,
            'created_at'     => $book->created_at->format('Y-m-d'),
        ];

        // ✅ Only return selected columns
        return collect($this->selectedColumns)
            ->map(fn($col) => $map[$col] ?? '')
            ->values()
            ->toArray();
    }
}
