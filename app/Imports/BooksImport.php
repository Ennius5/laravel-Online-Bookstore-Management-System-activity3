<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Category;
use App\Models\ImportLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;

class BooksImport implements
    ToCollection,
    WithHeadingRow,
    WithChunkReading,
    WithValidation,
    SkipsOnFailure,
    WithBatchInserts,
    WithEvents,
    ShouldQueue
{
    use SkipsFailures;

    protected int $importLogId;
    protected string $duplicateHandling;

    public function __construct(int $importLogId, string $duplicateHandling = 'update')
    {
        $this->importLogId       = $importLogId;
        $this->duplicateHandling = $duplicateHandling;
    }

    public function collection(Collection $rows)
    {
        $log = ImportLog::find($this->importLogId);

        foreach ($rows as $row) {
            $categoryId = Category::where('name', $row['category'])->value('id');

            $book = [
                'isbn'           => $row['isbn'],
                'title'          => $row['title'],
                'author'         => $row['author'],
                'price'          => $row['price'],
                'stock_quantity' => $row['stock'],
                'category_id'    => $categoryId,
                'description'    => $row['description'] ?? null,
            ];

            if ($this->duplicateHandling === 'update') {
                Book::updateOrCreate(['isbn' => $row['isbn']], $book);
            } else {
                // Skip if ISBN already exists
                Book::firstOrCreate(['isbn' => $row['isbn']], $book);
            }
        }

        // Update progress
        $log->increment('processed_rows', count($rows));
        $log->update(['status' => 'processing']);
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                ImportLog::find($this->importLogId)
                    ->update(['status' => 'processing']);
            },
            AfterImport::class => function (AfterImport $event) {
                $log = ImportLog::find($this->importLogId);

                // Count failures accumulated by SkipsFailures trait
                $failureCount = count($this->failures());
                $errors = collect($this->failures())->map(fn($f) => [
                    'row'    => $f->row(),
                    'errors' => $f->errors(),
                    'values' => $f->values(),
                ])->toArray();

                $log->update([
                    'status'      => $failureCount > 0 ? 'completed' : 'completed',
                    'failed_rows' => $failureCount,
                    'errors'      => $errors,
                ]);
            },
        ];
    }

    public function rules(): array
    {
        return [
            'isbn'     => [
                'required',
                'regex:/^(?:(?:\d{9}[\dX])|(?:97[89]\d{10}))$/',
            ],
            'title'    => ['required', 'max:255'],
            'author'   => ['required'],
            'price'    => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'stock'    => ['required', 'integer', 'min:0'],
            'category' => ['required', 'exists:categories,name'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'isbn.regex'      => 'The ISBN must be a valid ISBN-10 or ISBN-13 format.',
            'price.min'       => 'Price must be a positive number.',
            'stock.min'       => 'Stock cannot be negative.',
            'category.exists' => 'The category does not exist in the system.',
        ];
    }

    public function chunkSize(): int { return 1000; }
    public function batchSize(): int { return 1000; }
}
