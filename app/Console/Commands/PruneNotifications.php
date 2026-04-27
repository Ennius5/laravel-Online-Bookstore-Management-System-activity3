<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

class PruneNotifications extends Command
{
    protected $signature = 'notification:prune';
    protected $description = 'Delete notifications older than 90 days';

    public function handle()
    {
        $count = DatabaseNotification::where('created_at', '<', now()->subDays(90))->delete();
        $this->info("Deleted {$count} old notifications.");
        \Log::info("Notification prune: removed {$count} records.");
    }
}
