<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BenchmarkBookQueries extends Command
{
    protected $signature   = 'benchmark:books {--iterations=100}';
    protected $description = 'Benchmark critical book queries against performance targets';

    // Performance targets from lab spec (ms)
    const TARGETS = [
        'isbn_lookup'    => 50,
        'catalog_list'   => 100,
        'category_filter'=> 150,
        'fulltext_search'=> 300,
    ];

    public function handle(): int
    {
        $iterations = (int) $this->option('iterations');
        $this->info("Running benchmarks ({$iterations} iterations each)...\n");

        $results = [
            'isbn_lookup'     => $this->benchmarkIsbnLookup($iterations),
            'catalog_list'    => $this->benchmarkCatalogList($iterations),
            'category_filter' => $this->benchmarkCategoryFilter($iterations),
            'fulltext_search' => $this->benchmarkFulltextSearch(
                min($iterations, 50) // Lab spec: 50 iterations for fulltext
            ),
        ];

        $this->displayResults($results);

        // Return non-zero exit code if any target is missed
        $failed = collect($results)->filter(
            fn($r, $key) => $r['avg'] > self::TARGETS[$key]
        );

        if ($failed->isNotEmpty()) {
            $this->error("\n❌ " . $failed->count() . " benchmark(s) missed target.");
            return self::FAILURE;
        }

        $this->info("\n✅ All benchmarks passed!");
        return self::SUCCESS;
    }

    private function benchmarkIsbnLookup(int $iterations): array
    {
        // Get a random ISBN to look up
        $isbn = DB::table('books')->inRandomOrder()->value('isbn');

        return $this->runBenchmark($iterations, function () use ($isbn) {
            Book::where('isbn', $isbn)->first();
        });
    }

    private function benchmarkCatalogList(int $iterations): array
    {
        return $this->runBenchmark($iterations, function () {
            Book::select(['id', 'isbn', 'title', 'author', 'price', 'stock_quantity'])
                ->where('is_active', true)
                ->orderBy('published_at', 'desc')
                ->orderBy('id', 'desc')
                ->cursorPaginate(100);
        });
    }

    private function benchmarkCategoryFilter(int $iterations): array
    {
        $categoryId = DB::table('books')->inRandomOrder()->value('category_id');

        return $this->runBenchmark($iterations, function () use ($categoryId) {
            Book::select(['id', 'isbn', 'title', 'price', 'stock_quantity'])
                ->where('category_id', $categoryId)
                ->where('is_active', true)
                ->orderBy('id', 'desc')
                ->cursorPaginate(100);
        });
    }

    private function benchmarkFulltextSearch(int $iterations): array
    {
        return $this->runBenchmark($iterations, function () {
            Book::whereFullText(['title', 'description'], 'the')
                ->where('is_active', true)
                ->select(['id', 'isbn', 'title', 'author', 'price'])
                ->limit(50)
                ->get();
        });
    }

    private function runBenchmark(int $iterations, callable $query): array
    {
        $times = [];

        // Warmup pass
        $query();

        for ($i = 0; $i < $iterations; $i++) {
            $start   = microtime(true);
            $query();
            $times[] = (microtime(true) - $start) * 1000; // Convert to ms
        }

        return [
            'avg'   => round(array_sum($times) / count($times), 2),
            'min'   => round(min($times), 2),
            'max'   => round(max($times), 2),
            'total' => round(array_sum($times), 2),
        ];
    }

    private function displayResults(array $results): void
    {
        $headers = ['Query', 'Avg (ms)', 'Min (ms)', 'Max (ms)', 'Target (ms)', 'Status'];
        $rows    = [];

        foreach ($results as $key => $result) {
            $target = self::TARGETS[$key];
            $status = $result['avg'] <= $target ? '✅ PASS' : '❌ FAIL';

            $rows[] = [
                str_replace('_', ' ', ucfirst($key)),
                $result['avg'],
                $result['min'],
                $result['max'],
                $target,
                $status,
            ];
        }

        $this->table($headers, $rows);
    }
}
