<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RotateLogs extends Command
{
    protected $signature = 'log:rotate';
    protected $description = 'Archive and compress old log files';

    public function handle()
    {
        $logPath = storage_path('logs');
        $archivePath = $logPath . '/archive';

        if (! File::isDirectory($archivePath)) {
            File::makeDirectory($archivePath, 0755, true);
        }

        foreach (File::files($logPath) as $file) {
            // Only rotate files older than 7 days
            if ($file->getMTime() < now()->subDays(7)->timestamp) {
                $archiveName = $archivePath . '/' . $file->getFilename() . '.gz';
                $gz = gzopen($archiveName, 'w9');
                gzwrite($gz, file_get_contents($file->getRealPath()));
                gzclose($gz);

                File::delete($file);
            }
        }

        $this->info('Log rotation completed.');
        \Log::info('Log rotation completed.');
    }
}
