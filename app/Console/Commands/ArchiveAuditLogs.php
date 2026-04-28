<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OwenIt\Auditing\Models\Audit;

class ArchiveAuditLogs extends Command
{
    protected $signature = 'audit:archive';
    protected $description = 'Archive audit logs older than 1 year';

    public function handle()
    {
        $count = Audit::where('created_at', '<', now()->subYear())->delete();
        $this->info("Archived {$count} audit records.");
        \Log::info("audit:archive removed {$count} old records.");
    }
}
