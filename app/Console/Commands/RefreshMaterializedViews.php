<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshMaterializedViews extends Command
{
    protected $signature   = 'app:refresh-materialized-views';
    protected $description = 'Refresh all materialized view tables with latest data';

public function handle(): int
{
    $this->info('Refreshing materialized views...');
    $start = microtime(true);

    // ✅ No DB::transaction() — TRUNCATE causes implicit commit in MariaDB
    DB::table('mv_bestseller_stats')->truncate();

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

    $elapsed = round(microtime(true) - $start, 2);
    $this->info("✅ Materialized views refreshed in {$elapsed}s");

    return self::SUCCESS;
}
}
