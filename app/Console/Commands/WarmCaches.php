<?php

namespace App\Console\Commands;

use App\Jobs\WarmCategoryCache;
use App\Models\Category;
use Illuminate\Console\Command;

class WarmCaches extends Command
{
    protected $signature   = 'cache:warm';
    protected $description = 'Pre-warm Redis caches for all categories';

    public function handle(): void
    {
        $categories = Category::all();

        $this->info("Warming caches for {$categories->count()} categories...");
        $bar = $this->output->createProgressBar($categories->count());
        $bar->start();

        foreach ($categories as $category) {
            WarmCategoryCache::dispatch($category->id);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Cache warming jobs dispatched!");
    }
}
