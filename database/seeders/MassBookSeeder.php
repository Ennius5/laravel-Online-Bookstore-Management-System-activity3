<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MassBookSeeder extends Seeder
{
    private const CHUNK_SIZE    = 4000;
    private const TOTAL_RECORDS = 200000;

    public function run(): void
    {
        $inserted = 0;
        $startTime = microtime(true);

        // Disable query logging to save memory
        DB::disableQueryLog();

        $this->command->info('Starting 1M book seeding...');
        $bar = $this->command->getOutput()->createProgressBar(self::TOTAL_RECORDS);
        $bar->start();

        while ($inserted < self::TOTAL_RECORDS) {
            $batchSize = min(self::CHUNK_SIZE, self::TOTAL_RECORDS - $inserted);
            DB::reconnect();
            // make() generates models WITHOUT persisting — no memory bloat
            $books = Book::factory()->count($batchSize)->make()->map(fn($book) => $book->getAttributes())->toArray();

            // Raw insert bypasses Eloquent overhead
            DB::table('books')->insertOrIgnore($books); // ✅ skips duplicates silently

            $inserted += $batchSize;
            $bar->advance($batchSize);

            // Force garbage collection every 10 chunks (50,000 records)
            if ($inserted % (self::CHUNK_SIZE * 10) === 0) {
                unset($books);
                gc_collect_cycles();

                $elapsed  = round(microtime(true) - $startTime, 1);
                $rate     = round($inserted / $elapsed);
                $this->command->line("\n  [{$elapsed}s] {$inserted} inserted @ {$rate} rec/s");
            }
        }

        $bar->finish();

        $elapsed = round(microtime(true) - $startTime, 1);
        $this->command->info("\n✅ Done! {$inserted} books seeded in {$elapsed} seconds.");


    }
}
