<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
public function up(): void
{
    // Step 1 — Fill NULL published_at values (already done but safe to re-run)
    DB::statement("UPDATE books SET published_at = '1970-01-01' WHERE published_at IS NULL");

    // Step 2 — Make published_at NOT NULL (already done but safe to re-run)
    DB::statement("ALTER TABLE books MODIFY published_at DATE NOT NULL DEFAULT '1970-01-01'");

    // Step 3 — Drop foreign key only if it exists
    $foreignKeys = collect(DB::select("
        SELECT CONSTRAINT_NAME
        FROM information_schema.TABLE_CONSTRAINTS
        WHERE TABLE_NAME = 'books'
        AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        AND TABLE_SCHEMA = DATABASE()
    "))->pluck('CONSTRAINT_NAME')->toArray();

    if (in_array('books_category_id_foreign', $foreignKeys)) {
        DB::statement('ALTER TABLE books DROP FOREIGN KEY books_category_id_foreign');
    }

    // Step 4 — Modify primary key to include published_at
    DB::statement('ALTER TABLE books DROP PRIMARY KEY, ADD PRIMARY KEY (id, published_at)');

    // Step 5 — Apply range partitioning
    DB::statement("
        ALTER TABLE books PARTITION BY RANGE (YEAR(published_at)) (
            PARTITION p_null   VALUES LESS THAN (1990),
            PARTITION p1990    VALUES LESS THAN (1995),
            PARTITION p1995    VALUES LESS THAN (2000),
            PARTITION p2000    VALUES LESS THAN (2005),
            PARTITION p2005    VALUES LESS THAN (2010),
            PARTITION p2010    VALUES LESS THAN (2015),
            PARTITION p2015    VALUES LESS THAN (2020),
            PARTITION p2020    VALUES LESS THAN (2025),
            PARTITION p_future VALUES LESS THAN MAXVALUE
        )
    ");
}
};
